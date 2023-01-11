import ApiResponse from "./api-response";
import ApiProblemResponse from "./api-problem-response";

export default class FragmentClient {

    /**
     * @param {string} endpoint
     *
     * @return NodeList|undefined
     */
    async get(endpoint) {
        const response = await fetch(endpoint);

        if (!response.ok) {
            console.error(response);
            throw new Error('Invalid response received!');
        }

        const body = await response.text();

        const element = document.createElement('template');
        element.innerHTML = body;

        if (!element.content.hasChildNodes()) {
            return undefined;
        }

        return element.content.childNodes;
    }
}