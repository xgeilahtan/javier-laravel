document.addEventListener("DOMContentLoaded", function () {
    const serviceCards = document.querySelectorAll(".service-card");
    const modal = document.getElementById("serviceModal");
    const btnClose = document.getElementById("btnCloseModal");

    const categoryFilter = document.getElementById("category-filter");
    const professionalFilter = document.getElementById("professional-filter");

    let swiperMain = null;
    let swiperThumbs = null;

    function initSwiper() {
        if (swiperMain) swiperMain.destroy(true, true);
        if (swiperThumbs) swiperThumbs.destroy(true, true);

        swiperThumbs = new Swiper(".mySwiper", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });

        swiperMain = new Swiper(".mySwiper2", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: { swiper: swiperThumbs },
        });
    }

    serviceCards.forEach((card) => {
        card.addEventListener("click", function () {
            const name = this.dataset.name;
            const desc = this.dataset.description;
            const price = this.dataset.price;
            const duration = this.dataset.duration;
            const category = this.dataset.category;

            let images = [];
            try {
                images = JSON.parse(this.dataset.images || "[]");
            } catch (e) {
                console.error("Erro JSON imagens:", e);
                images = [];
            }

            document.getElementById("modalTitle").innerText = name;
            document.getElementById("modalDescription").innerText =
                desc || "Sem descrição.";
            document.getElementById("modalPrice").innerText = price;
            document.getElementById("modalDuration").innerText = duration;

            const catEl = document.getElementById("modalCategory");
            if (catEl) catEl.innerText = category;

            const wrapperMain = document.getElementById("swiperWrapperMain");
            const wrapperThumbs = document.getElementById(
                "swiperWrapperThumbs"
            );

            wrapperMain.innerHTML = "";
            wrapperThumbs.innerHTML = "";

            if (images.length === 0) {
                wrapperMain.innerHTML = `<div class="swiper-slide" style="color:#fff; display:flex; justify-content:center; align-items:center;">Sem imagem</div>`;
            } else {
                images.forEach((url) => {
                    wrapperMain.innerHTML += `<div class="swiper-slide"><img src="${url}" alt="${name}" /></div>`;
                    wrapperThumbs.innerHTML += `<div class="swiper-slide"><img src="${url}" alt="thumb" /></div>`;
                });
            }

            modal.classList.add("active");
            document.body.style.overflow = "hidden";
            setTimeout(() => initSwiper(), 10);
        });
    });

    function closeModalAction() {
        modal.classList.remove("active");
        document.body.style.overflow = "auto";
    }

    if (btnClose) btnClose.addEventListener("click", closeModalAction);

    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) closeModalAction();
        });
    }

    function filterServices() {
        const selectedCategory = categoryFilter.value.toLowerCase();
        const selectedProfessional = professionalFilter.value;

        serviceCards.forEach((card) => {
            const cardCategory = card.dataset.category.toLowerCase();
            const cardProfessionals = JSON.parse(card.dataset.professionals);

            const categoryMatch =
                selectedCategory === "todas" ||
                cardCategory === selectedCategory;

            const professionalMatch =
                selectedProfessional === "todos" ||
                cardProfessionals.includes(parseInt(selectedProfessional));

            card.style.display =
                categoryMatch && professionalMatch ? "block" : "none";
        });
    }

    categoryFilter?.addEventListener("change", filterServices);
    professionalFilter?.addEventListener("change", filterServices);
});
