const state = {
    user: {
        token: sessionStorage.getItem('TOKEN'),
        data: {},
    },
    products: {
        loading: false,
        data: [],
    }
};
export default state;
