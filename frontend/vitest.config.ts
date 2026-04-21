import { fileURLToPath } from 'node:url'
import { mergeConfig, defineConfig, coverageConfigDefaults } from 'vitest/config'
import viteConfig from './vite.config'

export default mergeConfig(
  viteConfig,
  defineConfig({
    test: {
      environment: 'jsdom',
      root: fileURLToPath(new URL('./', import.meta.url)),
      coverage: {
        provider: 'v8',
        reporter: ['text', 'lcov'],
        include: [
          'src'
        ],
        exclude: [
          ...coverageConfigDefaults.exclude,
          '*.config.ts',
        ],
      },
    }
  })
)
