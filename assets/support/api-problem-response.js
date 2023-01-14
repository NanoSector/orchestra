/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

export default class ApiProblemResponse {
    /** @type {string} */
    type

    /** @type {string} */
    title

    /** @type {number} */
    status

    /** @type {string} */
    detail

    /**
     * @param {string} type
     * @param {string} title
     * @param {number} status
     * @param {string} detail
     */
    constructor(type, title, status, detail) {
        this.type = type;
        this.title = title;
        this.status = status;
        this.detail = detail;
    }

    /**
     * @param {Object} data
     */
    static fromResponse(data) {
        if (!this.conforms(data)) {
            throw new Error('Cannot construct ApiProblemResponse from non-conforming data');
        }

        return new ApiProblemResponse(
            data.type ?? 'orchestra://problem/unknown',
            data.title ?? '',
            data.status ?? 0,
            data.detail ?? ''
        );
    }

    /**
     * @param {Object} data
     */
    static conforms(data) {
        return data.hasOwnProperty('type')
            && data.hasOwnProperty('title')
            && data.hasOwnProperty('status');
    }


}