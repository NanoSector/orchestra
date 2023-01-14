/*
 * Copyright (c) 2023 NanoSector & Orchestra contributors
 *
 * This source code is licensed under the MIT license. See LICENSE for details.
 */

import {Controller} from '@hotwired/stimulus';
import ApiClient from "../../support/api-client";
import ApiResponse from "../../support/api-response";
import FragmentClient from "../../support/fragment-client";

/* stimulusFetch: 'lazy' */
export default class extends Controller {

    static values = {
        pinApiEndpoint: String,
        unpinApiEndpoint: String,
        fragmentEndpoint: String
    };

    initialize() {
        this.apiClient = new ApiClient();
        this.fragmentClient = new FragmentClient();
    }

    async pin() {
        console.log(this.pinApiEndpointValue, this.unpinApiEndpointValue, this.fragmentEndpointValue);

        const apiResponse = await this.apiClient.post(this.pinApiEndpointValue);

        if (ApiResponse.conforms(apiResponse) && apiResponse.ok) {
            const fragmentResponse = await this.fragmentClient.get(this.fragmentEndpointValue);

            if (fragmentResponse !== undefined) {
                this.element.replaceChildren(...fragmentResponse);
            }
        } else {
            console.error('Invalid API response received', apiResponse, ApiResponse.conforms(apiResponse));
        }
    }

    async unpin() {
        console.log(this.pinApiEndpointValue, this.unpinApiEndpointValue, this.fragmentEndpointValue);
        const apiResponse = await this.apiClient.post(this.unpinApiEndpointValue);

        if (ApiResponse.conforms(apiResponse) && apiResponse.ok) {
            const fragmentResponse = await this.fragmentClient.get(this.fragmentEndpointValue);

            if (fragmentResponse !== undefined) {
                this.element.replaceChildren(...fragmentResponse);
            }
        } else {
            console.error('Invalid API response received', apiResponse, ApiResponse.conforms(apiResponse));
        }
    }
}