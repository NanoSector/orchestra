import ApiResponse from "./api-response";
import ApiProblemResponse from "./api-problem-response";

export default class ApiClient {

    /**
     * @param {string} endpoint
     *
     * @return ApiResponse|ApiProblemResponse
     */
    async get(endpoint) {
        const response = await fetch(endpoint);

        if (!response.ok) {
            console.error(response);
            throw new Error('Invalid response received!');
        }

        const body = await response.json();

        if (ApiProblemResponse.conforms(body)) {
            return ApiProblemResponse.fromResponse(body);
        }

        return ApiResponse.fromResponse(body);
    }

    /**
     * @param {string} endpoint
     *
     * @return ApiResponse|ApiProblemResponse
     */
    async post(endpoint) {
        const response = await fetch(endpoint, {method: 'POST'});

        if (!response.ok) {
            console.error(response);
            throw new Error('Invalid response received!');
        }

        const body = await response.json();

        if (ApiProblemResponse.conforms(body)) {
            return ApiProblemResponse.fromResponse(body);
        }

        return ApiResponse.fromResponse(body);
    }
}