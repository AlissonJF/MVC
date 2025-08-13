var BASEURL = "http://localhost/MVC/";

async function getUrl(url) {
    const response = await axios.get(url);
    return response;
}

async function postForm(url, form) {
    let data = Object.fromEntries(new FormData(form));
    const response = await axios.post(url, data);
    return response;
}

async function deleteItem(url, par) {
    const response = await axios.delete(url, { params: par });
    return response;
}


axios.interceptors.request.use(function(config) {
    // Do something before request is sent
    document.body.classList.add('loading-indicator');
    return config;
}, function(error) {
    // Do something with request error
    return Promise.reject(error);
});

// Add a response interceptor
axios.interceptors.response.use(function(response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    document.body.classList.remove('loading-indicator');
    return response;
}, function(error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
});


function hideEdit() {
    document.querySelector("#botoesedit").style.display = "none";
    document.querySelector("#botaocad").style.display = "block";
}

function showEdit() {
    document.querySelector("#botoesedit").style.display = "block";
    document.querySelector("#botaocad").style.display = "none";
}

function activateLabel(el) {
    el.focus();
}
