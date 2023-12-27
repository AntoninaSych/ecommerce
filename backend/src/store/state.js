import {PRODUCTS_PER_PAGE} from "../constants.js";

const state = {
    user: {
        token: sessionStorage.getItem('TOKEN'),
        data: {},
    },
    products: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    orders: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    }
};
export default state;
