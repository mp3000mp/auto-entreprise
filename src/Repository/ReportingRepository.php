<?php

namespace App\Repository;

use \Doctrine\DBAL\Connection;

class ReportingRepository
{
    protected $conn;
    private $interval = 'Q';

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }


    /**
     * @return string
     */
    private function generatedateField($reportType)
    {

        if($this->interval == 'M'){
            return "DATE_FORMAT(o.".$reportType.", '%m/%Y')";
        }elseif($this->interval == 'Y'){
            return "DATE_FORMAT(o.".$reportType.", '%Y')";
        }else{
            return "CONCAT('Q',QUARTER(o.".$reportType."),'/',YEAR(o.".$reportType."))";
        }
    }

    /**
     * @param $interval "M,Q,Y"
     */
    public function setInterval($interval){
        $this->interval = $interval;
    }


    public function createXTable()
    {

        $now = new \DateTime();
        $d = new \DateTime('2018-07-01');
        $i = 1;
        $rows = [];
        while($d <= $now){
            $arr = [
                "$i AS id",
                "'".$d->format('m/Y')."' AS M",
                "'Q".(ceil($d->format('n') / 3)).'/'.$d->format('Y')."' AS Q",
                "'".$d->format('Y')."' AS Y",
            ];
            $rows[] = ' SELECT ' . implode(', ',$arr);
            $d->modify('+1 month');
            $i++;
        }
        $table = implode(' UNION ', $rows);

        return $table;

    }


    /**
     * @param $reportType
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function rptgTurnover($reportType)
    {

        $reportDate = [
            'turnover' => 'payed_at',
            'billed' => 'billed_at',
            'purchased' => 'purchased_at',
        ];

        $dField = $this->generatedateField($reportDate[$reportType]);

        $sql = "
            SELECT d.$this->interval AS x, IFNULL(t.y,0) AS y
            FROM (" . $this->createXTable() . ") AS d
            LEFT JOIN (
                SELECT $dField AS x, (SUM(tr.sold_days)*t.average_daily_rate) AS y FROM opportunity o
                INNER JOIN tender t ON t.opportunity_id = o.id
                INNER JOIN tender_row tr ON tr.tender_id = t.id
                WHERE o.".$reportDate[$reportType]." IS NOT NULL
                GROUP BY $dField
            ) AS t ON d.$this->interval = t.x
            ORDER BY d.id
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([]);

        $data = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

        return $this->convertChartJs($data);

    }



    private function convertChartJs($data)
    {
        $structuredData = [
            'labels' => array_keys($data),
            'datasets' => [
                [
                    'label' => '',
                    'backgroundColor' => '',
                    'data' => array_values($data),
                ]
            ]
        ];
        return $structuredData;
    }

}
