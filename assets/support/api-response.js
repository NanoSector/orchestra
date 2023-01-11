export default class ApiResponse {
    /** @type {boolean} */
    ok

    /** @type {Object|string} */
    payload

    /** @type {Map<string, string>} */
    links

    /**
     * @param {boolean} ok
     * @param {Object|string} payload
     * @param {Map<string, string>} links
     */
    constructor(ok, payload, links) {
        this.ok = ok;
        this.payload = payload;
        this.links = links;
    }

    /**
     * @param {Object} data
     */
    static fromResponse(data) {
        if (!this.conforms(data)) {
            throw new Error('Cannot construct ApiResponse from non-conforming data');
        }

        return new ApiResponse(data.ok ?? false, data.payload ?? {}, data.links ?? {});
    }

    /**
     * @param {Object} data
     */
    static conforms(data) {
        return data.hasOwnProperty('ok') && data.hasOwnProperty('payload');
    }
}