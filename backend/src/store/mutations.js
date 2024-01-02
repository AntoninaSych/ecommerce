export function setUser(state, user) {
  state.user.data = user;
}

export function setToken(state, token) {
    state.user.token = token;
    if (token) {
        sessionStorage.setItem('TOKEN', token);
    } else {
        sessionStorage.removeItem('TOKEN')
    }
}

export function setProducts(state, [loading, response = null]) {
    if (response) {
        state.products = {
            data: response.data,
            links: response.meta.links,
            page: response.meta.current_page,
            limit: response.meta.per_page,
            from: response.meta.from,
            to: response.meta.to,
            total: response.meta.total
        }
    }
    state.products.loading = loading;
}


export function setOrders(state, [loading, response = null]) {
    if (response) {
        state.orders = {
            data: response.data,
            links: response.meta.links,
            page: response.meta.current_page,
            limit: response.meta.per_page,
            from: response.meta.from,
            to: response.meta.to,
            total: response.meta.total
        }
    }
    state.orders.loading = loading;

}


export function setUsers(state, [loading, data = null]) {

    if (data) {
        state.users = {
            ...state.users,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total,
        }
    }
    state.users.loading = loading;
}


export function showToast(state, message) {
    state.toast.show = true;
    state.toast.message = message;
}

export function hideToast(state) {
    state.toast.show = false;
    state.toast.message = '';
}

export function setCustomers(state, [loading, data = null]) {

    if (data) {
        state.customers = {
            ...state.customers,
            data: data.data,
            links: data.meta?.links,
            page: data.meta.current_page,
            limit: data.meta.per_page,
            from: data.meta.from,
            to: data.meta.to,
            total: data.meta.total,
        }
    }
    state.customers.loading = loading;
}

export function setCountries(state, countries) {
    state.countries = countries.data;
}

export function setCategories(state, [loading, data = null]) {

    if (data) {
        state.categories = {
            ...state.categories,
            data: data.data,
        }
    }

    state.categories.loading = loading;
}