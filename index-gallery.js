document.addEventListener("DOMContentLoaded", function () {
    var sliderPrimary = document.getElementById("top-slider-image-primary");
    var sliderSecondary = document.getElementById("top-slider-image-secondary");
    var grid = document.getElementById("photo-gallery-grid");
    var refreshButton = document.getElementById("photo-gallery-refresh");

    var photos = [
        "186032.jpg",
        "186058.jpg",
        "186059.jpg",
        "186060.jpg",
        "186061.jpg",
        "186062.jpg",
        "186063.jpg",
        "186064.jpg",
        "186065.jpg",
        "186066.jpg",
        "186067.jpg",
        "186068.jpg",
        "186069.jpg",
        "186070.jpg",
        "186071.jpg",
        "186072.jpg",
        "186073.jpg",
        "186074.jpg",
        "186075.jpg",
        "186076.jpg",
        "186077.jpg",
        "186078.jpg",
        "186079.jpg",
        "186080.jpg",
        "186081.jpg",
        "186082.jpg",
        "186083.jpg",
        "186084.jpg",
        "186085.jpg",
        "186086.jpg",
        "186087.jpg",
        "186088.jpg",
        "186089.jpg"
    ];
    var motions = [
        "motion-zoom",
        "motion-slide-left",
        "motion-slide-right",
        "motion-rise",
        "motion-drift"
    ];

    function shuffleList(list) {
        var shuffled = list.slice();
        for (var i = shuffled.length - 1; i > 0; i -= 1) {
            var j = Math.floor(Math.random() * (i + 1));
            var temp = shuffled[i];
            shuffled[i] = shuffled[j];
            shuffled[j] = temp;
        }
        return shuffled;
    }

    function pickRandomMotion() {
        return motions[Math.floor(Math.random() * motions.length)];
    }

    function resetMotionClasses(image) {
        for (var i = 0; i < motions.length; i += 1) {
            image.classList.remove(motions[i]);
        }
    }

    if (sliderPrimary && sliderSecondary && photos.length) {
        var sliderPhotos = shuffleList(photos);
        var currentIndex = 0;
        var activeImage = sliderPrimary;
        var inactiveImage = sliderSecondary;

        function setSlide(image, index) {
            image.src = "photos/" + sliderPhotos[index];
            image.alt = "E34 535i slide " + (index + 1);
        }

        setSlide(activeImage, currentIndex);
        setSlide(inactiveImage, (currentIndex + 1) % sliderPhotos.length);
        activeImage.classList.add(pickRandomMotion());
        if (sliderPhotos.length > 1) {
            window.setInterval(function () {
                currentIndex = (currentIndex + 1) % sliderPhotos.length;
                setSlide(inactiveImage, currentIndex);
                resetMotionClasses(activeImage);
                resetMotionClasses(inactiveImage);
                inactiveImage.classList.add(pickRandomMotion());
                inactiveImage.classList.add("is-active");
                activeImage.classList.remove("is-active");

                var previous = activeImage;
                activeImage = inactiveImage;
                inactiveImage = previous;
            }, 3000);
        }
    }

    if (!grid) {
        return;
    }

    function renderGallery() {
        var selected = shuffleList(photos).slice(0, 20);
        grid.innerHTML = "";

        selected.forEach(function (filename, index) {
            var item = document.createElement("a");
            var image = document.createElement("img");

            item.className = "photo-gallery__item";
            item.href = "photos/" + filename;
            item.target = "_blank";
            item.rel = "noopener noreferrer";

            image.src = "photos/" + filename;
            image.alt = "E34 535i photo " + (index + 1);
            image.loading = "lazy";

            item.appendChild(image);
            grid.appendChild(item);
        });
    }

    renderGallery();

    if (refreshButton) {
        refreshButton.addEventListener("click", function () {
            renderGallery();
        });
    }
});
