export function getRandomInt(min: number, max: number): number {
  return Math.floor(min + Math.random() * max)
}

export function getFileIcon(extension: string): string {
  if (extension === 'pdf') {
    return 'file-pdf'
  }
  if (extension === 'docx') {
    return 'file-docx'
  }
  if (['gif', 'jpeg', 'jpg', 'png', 'svg', 'webp'].includes(extension)) {
    return 'file-zipper'
  }
  if (['rar', 'zip'].includes(extension)) {
    return 'file-zipper'
  }
  if (['csv'].includes(extension)) {
    return 'file-csv'
  }
  if (['xlsx'].includes(extension)) {
    return 'file-excel'
  }
  if (['css', 'go', 'html', 'js', 'less', 'php', 'py', 'scss', 'vba', 'vbs'].includes(extension)) {
    return 'file-audio'
  }
  if (['mp3', 'wav'].includes(extension)) {
    return 'file-audio'
  }
  if (['avi', 'mp4', 'wmv'].includes(extension)) {
    return 'file-video'
  }
  return 'file'
}
