import config from '@/misc/config'

export enum HttpMethodEnum {
  DELETE = 'DELETE',
  GET = 'GET',
  PATCH = 'PATCH',
  POST = 'POST',
  PUT = 'PUT'
}

type OnUnauthorizedCallback = (response: any) => void

export class ApiError {
  message = ''
}

export type ApiClientOptions = {
  ignoreResponse?: boolean
  isJson?: boolean
  headers: any
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
  public async query(
    httpMethod: HttpMethodEnum,
    url: string,
    json: any = null,
    options: ApiClientOptions = {}
  ) {
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
      delete fetchOptions.headers['Content-Type'] // mandatory so boundary will be set by browser
    } else if (json !== null) {
      fetchOptions.body = JSON.stringify(json)
    }
    try {
      const response = await fetch(this.baseUrl + url, fetchOptions)
      let jsonResponse = response
      if (options.isJson) {
        jsonResponse =
          options.ignoreResponse || response.status === 204 ? null : await response.json()
      }
      if (response.ok) {
        return await jsonResponse
      }
      if (response.status === 401 && this.onUnauthorizedCallback !== null) {
        this.onUnauthorizedCallback(jsonResponse)
      }
      throw jsonResponse
    } catch (err) {
      throw err
    }
  }

  public setOnUnauthorizedCallback(callback: OnUnauthorizedCallback) {
    this.onUnauthorizedCallback = callback
  }
}

export default new ApiClient()
