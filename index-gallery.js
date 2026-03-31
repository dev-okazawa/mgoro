document.addEventListener("DOMContentLoaded", function () {
    var sliderPrimary = document.getElementById("top-slider-image-primary");
    var sliderSecondary = document.getElementById("top-slider-image-secondary");
    var grid = document.getElementById("photo-gallery-grid");
    var refreshButton = document.getElementById("photo-gallery-refresh");

    var photos = [
        "186032.jpg",
        "186033.jpg",
        "186034.jpg",
        "186035.jpg",
        "186036.jpg",
        "186037.jpg",
        "186038.jpg",
        "186039.jpg",
        "186046.jpg",
        "186047.jpg",
        "186048.jpg",
        "186049.jpg",
        "186050.jpg",
        "186051.jpg",
        "186052.jpg",
        "186053.jpg",
        "186054.jpg",
        "186055.jpg",
        "186056.jpg",
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
    var initialPhoto = "186032.jpg";
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
        var sliderPhotos = [initialPhoto].concat(
            shuffleList(photos.filter(function (photo) {
                return photo !== initialPhoto;
            }))
        );
        var currentIndex = 0;
        var activeImage = sliderPrimary;
        var inactiveImage = sliderSecondary;
        var slideDelay = 3000;
        var fadeDuration = 1100;
        var isTransitioning = false;

        function setSlide(image, index) {
            image.src = "photos/" + sliderPhotos[index];
            image.alt = "E34 535i slide " + (index + 1);
        }

        function preloadSlide(index, callback) {
            var loader = new Image();
            var source = "photos/" + sliderPhotos[index];

            loader.onload = function () {
                callback(source, index);
            };
            loader.src = source;
        }

        function queueNextSlide() {
            if (sliderPhotos.length <= 1) {
                return;
            }

            window.setTimeout(function () {
                var nextIndex;
                var previous;

                if (isTransitioning) {
                    queueNextSlide();
                    return;
                }

                isTransitioning = true;
                nextIndex = (currentIndex + 1) % sliderPhotos.length;

                preloadSlide(nextIndex, function (source, loadedIndex) {
                    inactiveImage.classList.remove("is-exiting");
                    inactiveImage.src = source;
                    inactiveImage.alt = "E34 535i slide " + (loadedIndex + 1);
                    resetMotionClasses(activeImage);
                    resetMotionClasses(inactiveImage);
                    inactiveImage.classList.add(pickRandomMotion());
                    inactiveImage.classList.add("is-active");
                    activeImage.classList.add("is-exiting");
                    activeImage.classList.remove("is-active");

                    previous = activeImage;
                    activeImage = inactiveImage;
                    inactiveImage = previous;
                    currentIndex = loadedIndex;
                    window.setTimeout(function () {
                        inactiveImage.classList.remove("is-exiting");
                        isTransitioning = false;
                        queueNextSlide();
                    }, fadeDuration);
                });
            }, slideDelay);
        }

        setSlide(activeImage, currentIndex);
        setSlide(inactiveImage, (currentIndex + 1) % sliderPhotos.length);
        queueNextSlide();
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

            image.src = "photos/thumbs/" + filename;
            image.alt = "E34 535i photo " + (index + 1);
            image.loading = "lazy";
            image.decoding = "async";

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
