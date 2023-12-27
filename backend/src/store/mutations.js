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
