<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class ReportingRepository
{
    /**
     * @var Connection
     */
    protected $conn;
    /**
     * @var string
     */
    private $interval = 'Q';

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    private function generatedateField(string $reportType): string
    {
        if ('M' == $this->interval) {
            return 'DATE_FORMAT(o.'.$reportType.", '%m/%Y')";
        } elseif ('Y' == $this->interval) {
            return 'DATE_FORMAT(o.'.$reportType.", '%Y')";
        } else {
            return "CONCAT('Q',QUARTER(o.".$reportType."),'/',YEAR(o.".$reportType.'))';
        }
    }

    /**
     * interval can be "M,Q,Y".
     */
    public function setInterval(string $interval): void
    {
        $this->interval = $interval;
    }

    public function createXTable(): string
    {
        $now = new \DateTime();
        $d = new \DateTime('2018-07-01');
        $i = 1;
        $rows = [];
        while ($d <= $now) {
            $arr = [
                "$i AS id",
                "'".$d->format('m/Y')."' AS M",
                "'Q".(ceil($d->format('n') / 3)).'/'.$d->format('Y')."' AS Q",
                "'".$d->format('Y')."' AS Y",
            ];
            $rows[] = ' SELECT '.implode(', ', $arr);
            $d->modify('+1 month');
            ++$i;
        }
        $table = implode(' UNION ', $rows);

        return $table;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function rptgTurnover(string $reportType): array
    {
        $reportDate = [
            'turnover' => 'payed_at',
            'billed' => 'billed_at',
            'purchased' => 'purchased_at',
        ];

        $dField = $this->generatedateField($reportDate[$reportType]);

        $sql = "
            SELECT d.$this->interval AS x, IFNULL(t.y,0) AS y
            FROM (".$this->createXTable().") AS d
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

    private function convertChartJs(array $data): array
    {
        $structuredData = [
            'labels' => array_keys($data),
            'datasets' => [
                [
                    'label' => '',
                    'backgroundColor' => '',
                    'data' => array_values($data),
                ],
            ],
        ];

        return $structuredData;
    }
}
