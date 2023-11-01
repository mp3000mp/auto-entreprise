import config from '@/misc/config'

export enum HttpMethodEnum {
  DELETE = 'DELETE',
  GET = 'GET',
  PATCH = 'PATCH',
  POST = 'POST',
  PUT = 'PUT'
}

export class ApiError {
  message = ''
  error = ''
  statusCode = 0
}

export class ApiClient {
  baseUrl = config.backendBaseUrl
  headers = {
    Authorization: 'Bearer ' + config.backendApiKey,
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }

  public async query(httpMethod: HttpMethodEnum, url: string, json: any = null) {
    const options: RequestInit = {
      method: httpMethod,
      headers: this.headers
    }
    if (json !== null) {
      options.body = JSON.stringify(json)
    }
    try {
      const response = await fetch(this.baseUrl + url, options)
      const jsonResponse = await response.json()
      if (response.ok) {
        return await jsonResponse
      }
      throw jsonResponse
    } catch (err) {
      throw err
    }
  }
}

export default new ApiClient()
