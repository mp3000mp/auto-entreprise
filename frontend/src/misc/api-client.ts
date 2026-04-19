import config from '@/misc/config'

export enum HttpMethodEnum {
  DELETE = 'DELETE',
  GET = 'GET',
  PATCH = 'PATCH',
  POST = 'POST',
  PUT = 'PUT'
}

type OnUnauthorizedCallback = (response: unknown) => void

export class ApiError {
  message = ''
}

export type ApiClientOptions = {
  ignoreResponse?: boolean
  isJson?: boolean
  headers?: Record<string, string>
}
const defaultApiClientOptions = {
  ignoreResponse: false,
  isJson: true
}

class ApiClient {
  onUnauthorizedCallback = null as OnUnauthorizedCallback | null
  baseUrl = config.backendBaseUrl
  headers = {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }

  // todo rename json to body as it accepts FormData
  public async query<T>(
    httpMethod: HttpMethodEnum,
    url: string,
    json: FormData | object | null = null,
    options: ApiClientOptions = {}
  ): Promise<T> {
    options = {
      ...defaultApiClientOptions,
      ...options
    }
    const fetchOptions: RequestInit = {
      credentials: 'include' as RequestCredentials,
      method: httpMethod,
      headers: this.headers
    }
    if (json instanceof FormData) {
      fetchOptions.body = json
      delete (fetchOptions.headers as Record<string, string>)['Content-Type'] // mandatory so boundary will be set by browser
    } else if (json !== null) {
      fetchOptions.body = JSON.stringify(json)
    }
    // try {
    const response = await fetch(this.baseUrl + url, fetchOptions)
    let jsonResponse = response as T
    if (options.isJson) {
      if (options.ignoreResponse || response.status === 204) {
        return null as unknown as T
      }
      jsonResponse = await response.json()
    }
    if (response.ok) {
      return jsonResponse
    }
    if (response.status === 401 && this.onUnauthorizedCallback !== null) {
      this.onUnauthorizedCallback(jsonResponse)
    }
    throw jsonResponse
    // } catch (err) {
    //   throw err
    // }
  }

  public setOnUnauthorizedCallback(callback: OnUnauthorizedCallback) {
    this.onUnauthorizedCallback = callback
  }
}

export default new ApiClient()
