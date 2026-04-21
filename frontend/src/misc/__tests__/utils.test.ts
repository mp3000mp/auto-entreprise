import { describe, expect, test } from 'vitest'
import { getRandomInt, getFileIcon } from '@/misc/utils'

describe('getRandomInt', () => {
  test('returns an integer within range', () => {
    for (let i = 0; i < 100; i++) {
      const result = getRandomInt(1, 10)
      expect(result).toBeGreaterThanOrEqual(1)
      expect(result).toBeLessThan(11)
      expect(Number.isInteger(result)).toBe(true)
    }
  })
})

describe('getFileIcon', () => {
  test('returns file-pdf for pdf', () => {
    expect(getFileIcon('pdf')).toBe('file-pdf')
  })

  test('returns file-docx for docx', () => {
    expect(getFileIcon('docx')).toBe('file-docx')
  })

  test('returns file-zipper for image extensions', () => {
    for (const ext of ['gif', 'jpeg', 'jpg', 'png', 'svg', 'webp']) {
      expect(getFileIcon(ext)).toBe('file-zipper')
    }
  })

  test('returns file-zipper for archive extensions', () => {
    for (const ext of ['rar', 'zip']) {
      expect(getFileIcon(ext)).toBe('file-zipper')
    }
  })

  test('returns file-csv for csv', () => {
    expect(getFileIcon('csv')).toBe('file-csv')
  })

  test('returns file-excel for xlsx', () => {
    expect(getFileIcon('xlsx')).toBe('file-excel')
  })

  test('returns file-audio for code extensions', () => {
    for (const ext of ['css', 'go', 'html', 'js', 'less', 'php', 'py', 'scss', 'vba', 'vbs']) {
      expect(getFileIcon(ext)).toBe('file-audio')
    }
  })

  test('returns file-audio for audio extensions', () => {
    for (const ext of ['mp3', 'wav']) {
      expect(getFileIcon(ext)).toBe('file-audio')
    }
  })

  test('returns file-video for video extensions', () => {
    for (const ext of ['avi', 'mp4', 'wmv']) {
      expect(getFileIcon(ext)).toBe('file-video')
    }
  })

  test('returns file for unknown extension', () => {
    expect(getFileIcon('unknown')).toBe('file')
  })
})
