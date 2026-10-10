const page = document.body.dataset.page;

if (page) {
    const pageModule = await import(`./pages/${page}.js`);
    pageModule.init();
}
