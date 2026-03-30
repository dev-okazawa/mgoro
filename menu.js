(function () {
    var script = document.currentScript;
    if (!script) {
        var scripts = document.getElementsByTagName("script");
        script = scripts[scripts.length - 1] || null;
    }
    var root = script && script.dataset && script.dataset.root ? script.dataset.root : ".";
    var normalizedRoot = root === "." ? "." : root.replace(/\/+$/, "");
    var currentPath = window.location.pathname || "";

    function pathTo(target) {
        return normalizedRoot === "." ? target : normalizedRoot + "/" + target;
    }

    function endsWithText(text, suffix) {
        return text.slice(-suffix.length) === suffix;
    }

    function setClassState(element, className, enabled) {
        if (!element) {
            return;
        }

        if (element.classList) {
            if (enabled) {
                element.classList.add(className);
            } else {
                element.classList.remove(className);
            }
            return;
        }

        var classes = element.className ? element.className.split(/\s+/) : [];
        var filtered = [];
        var index;

        for (index = 0; index < classes.length; index += 1) {
            if (classes[index] && classes[index] !== className) {
                filtered.push(classes[index]);
            }
        }

        if (enabled) {
            filtered.push(className);
        }

        element.className = filtered.join(" ");
    }

    function isCurrent(target) {
        var normalizedTarget = target.replace(/^\.\//, "");
        return currentPath.indexOf("/" + normalizedTarget) !== -1 || endsWithText(currentPath, normalizedTarget);
    }

    function createLink(label, target) {
        var link = document.createElement("a");
        link.className = "site-menu__link";
        link.href = pathTo(target);
        link.textContent = label;
        if (isCurrent(target)) {
            setClassState(link, "is-current", true);
        }
        return link;
    }

    function createMenuItem(label, target) {
        var item = document.createElement("li");
        item.className = "site-menu__item";
        item.appendChild(createLink(label, target));
        return item;
    }

    function getTextContent(node) {
        return (node && (node.textContent || node.innerText || "")).replace(/\s+/g, " ").trim();
    }

    function buildOfflinePostLabel(linkNode) {
        var cell = linkNode;
        var linkText = getTextContent(linkNode);
        var cellText;
        var prefix;
        var index;

        while (cell && cell.tagName && cell.tagName.toLowerCase() !== "td") {
            cell = cell.parentNode;
        }

        cellText = getTextContent(cell);
        index = cellText.indexOf(linkText);
        prefix = index > 0 ? cellText.slice(0, index).replace(/\s+$/, "") : "";

        if (prefix) {
            return prefix + " " + linkText;
        }

        return linkText;
    }

    function ensureStyles() {
        var links = document.getElementsByTagName("link");
        var hasSharedStyle = false;
        var index;

        for (index = 0; index < links.length; index += 1) {
            if ((links[index].getAttribute("href") || "").indexOf("style.css") !== -1) {
                hasSharedStyle = true;
                break;
            }
        }

        if (!hasSharedStyle) {
            var fallbackStyle = document.createElement("link");
            var targetHead = document.head || document.getElementsByTagName("head")[0] || document.documentElement;
            fallbackStyle.rel = "stylesheet";
            fallbackStyle.href = pathTo("style.css");
            fallbackStyle.setAttribute("data-site-menu-style", "true");
            targetHead.appendChild(fallbackStyle);
        }
    }

    function createSection(label, items) {
        var item = document.createElement("li");
        var button = document.createElement("button");
        var sublist = document.createElement("ul");

        item.className = "site-menu__item";
        button.className = "site-menu__section-toggle";
        button.type = "button";
        button.textContent = label;
        button.setAttribute("aria-expanded", "false");

        sublist.className = "site-menu__sublist";
        items.forEach(function (entry) {
            var subItem = document.createElement("li");
            subItem.appendChild(createLink(entry.label, entry.target));
            sublist.appendChild(subItem);
        });

        button.addEventListener("click", function () {
            var open = button.getAttribute("aria-expanded") === "true";
            button.setAttribute("aria-expanded", open ? "false" : "true");
            setClassState(sublist, "is-open", !open);
        });

        item.appendChild(button);
        item.appendChild(sublist);
        return item;
    }

    function createHeader() {
        var header = document.createElement("header");
        var brand = document.createElement("a");
        var toggle = document.createElement("button");

        header.className = "site-header";
        brand.className = "site-header__brand";
        brand.href = pathTo("index.html");
        brand.textContent = "Mゴロー E34 535i";

        toggle.className = "site-header__toggle";
        toggle.type = "button";
        toggle.setAttribute("aria-expanded", "false");
        toggle.setAttribute("aria-label", "メニューを開く");
        toggle.innerHTML = "<span></span>";

        header.appendChild(brand);
        header.appendChild(toggle);
        return { header: header, toggle: toggle };
    }

    function createDrawer() {
        var backdrop = document.createElement("div");
        var drawer = document.createElement("aside");
        var drawerHeader = document.createElement("div");
        var title = document.createElement("p");
        var close = document.createElement("button");
        var list = document.createElement("ul");

        backdrop.className = "site-menu-backdrop";
        drawer.className = "site-menu";
        drawerHeader.className = "site-menu__header";
        title.className = "site-menu__title";
        title.textContent = "メニュー";
        close.className = "site-menu__close";
        close.type = "button";
        close.setAttribute("aria-label", "メニューを閉じる");
        close.textContent = "×";

        list.className = "site-menu__list";
        list.appendChild(createMenuItem("ホーム", "index.html"));
        list.appendChild(
            createSection("メンテナンス", [
                { label: "エンジン", target: "maintenance/engine.html" },
                { label: "足回り", target: "maintenance/ashimawari.html" },
                { label: "電装", target: "maintenance/densou.html" },
                { label: "外装", target: "maintenance/gaisou.html" },
                { label: "内装", target: "maintenance/naisou.html" },
                { label: "その他", target: "maintenance/others.html" }
            ])
        );
        list.appendChild(createMenuItem("オフラインMTG", "offlinemeeting/offlinemeetingtop.html"));

        drawerHeader.appendChild(title);
        drawerHeader.appendChild(close);
        drawer.appendChild(drawerHeader);
        drawer.appendChild(list);

        return { backdrop: backdrop, drawer: drawer, close: close };
    }

    function createFooter() {
        var footer = document.createElement("footer");
        var text = document.createElement("p");
        var year = document.createElement("span");

        footer.className = "site-footer";
        year.className = "site-footer__year";
        year.textContent = String(new Date().getFullYear());
        text.appendChild(year);
        text.appendChild(document.createTextNode(" © MGORO.NET All Right Reserved."));
        footer.appendChild(text);

        return footer;
    }

    function syncFooterYear() {
        var years = document.querySelectorAll(".site-footer__year");
        var value = String(new Date().getFullYear());
        var index;

        for (index = 0; index < years.length; index += 1) {
            years[index].textContent = value;
        }
    }

    function buildOfflineMenu() {
        if (!endsWithText(currentPath, "/offlinemeeting/offlinemeetingtop.html") &&
            !endsWithText(currentPath, "offlinemeetingtop.html")) {
            return;
        }

        var panel = document.createElement("section");
        var heading = document.createElement("h2");
        var note = document.createElement("p");
        var list = document.createElement("ul");
        var ranges = [
            { label: "2006-2009", start: 2006, end: 2009, years: [] },
            { label: "2010-2012", start: 2010, end: 2012, years: [] },
            { label: "2013-2015", start: 2013, end: 2015, years: [] }
        ];
        var nodes = document.body ? document.body.children : [];
        var currentYear = null;
        var index;

        for (index = 0; index < nodes.length; index += 1) {
            var node = nodes[index];
            var text = getTextContent(node);
            var yearMatch = text.match(/^(20\d{2})年$/);

            if (yearMatch) {
                currentYear = {
                    year: parseInt(yearMatch[1], 10),
                    label: yearMatch[1] + "年",
                    posts: []
                };
                continue;
            }

            if (!currentYear || !node.tagName || node.tagName.toLowerCase() !== "table") {
                continue;
            }

            var links = node.getElementsByTagName("a");
            var seen = {};
            var linkIndex;

            for (linkIndex = 0; linkIndex < links.length; linkIndex += 1) {
                var postLink = links[linkIndex];
                var href = postLink.getAttribute("href") || "";
                var label = buildOfflinePostLabel(postLink);

                if (!href || !label || href.charAt(0) === "#" || href.indexOf("javascript:") === 0) {
                    continue;
                }

                if (seen[href]) {
                    continue;
                }

                seen[href] = true;
                currentYear.posts.push({
                    label: label,
                    target: href
                });
            }

            if (currentYear.posts.length) {
                var rangeIndex;
                for (rangeIndex = 0; rangeIndex < ranges.length; rangeIndex += 1) {
                    if (currentYear.year >= ranges[rangeIndex].start && currentYear.year <= ranges[rangeIndex].end) {
                        ranges[rangeIndex].years.push(currentYear);
                        break;
                    }
                }
            }

            currentYear = null;
        }

        panel.className = "offline-menu";
        heading.className = "offline-menu__heading";
        heading.textContent = "オフラインMTG 年代別メニュー";
        note.className = "offline-menu__note";
        note.textContent = "年代を開くと、その年代の投稿一覧を表示します。";
        list.className = "offline-menu__list";

        ranges.forEach(function (range) {
            if (!range.years.length) {
                return;
            }

            var item = document.createElement("li");
            var button = document.createElement("button");
            var sublist = document.createElement("ul");
            var closeItem = document.createElement("li");
            var closeButton = document.createElement("button");

            item.className = "offline-menu__item";
            button.className = "offline-menu__range-toggle";
            button.type = "button";
            button.textContent = range.label;
            button.setAttribute("aria-expanded", "false");
            sublist.className = "offline-menu__sublist";

            range.years.forEach(function (yearGroup) {
                var yearItem = document.createElement("li");
                var yearLabel = document.createElement("p");
                var postsList = document.createElement("ul");

                yearItem.className = "offline-menu__year-group";
                yearLabel.className = "offline-menu__year-label";
                yearLabel.textContent = yearGroup.label;
                postsList.className = "offline-menu__posts";

                yearGroup.posts.forEach(function (post) {
                    var postItem = document.createElement("li");
                    var link = document.createElement("a");
                    link.className = "offline-menu__link";
                    link.href = post.target;
                    link.textContent = post.label;
                    postItem.appendChild(link);
                    postsList.appendChild(postItem);
                });

                yearItem.appendChild(yearLabel);
                yearItem.appendChild(postsList);
                sublist.appendChild(yearItem);
            });

            closeItem.className = "offline-menu__close-item";
            closeButton.className = "offline-menu__close-button";
            closeButton.type = "button";
            closeButton.textContent = "閉じる";
            closeButton.addEventListener("click", function () {
                button.setAttribute("aria-expanded", "false");
                setClassState(sublist, "is-open", false);
            });
            closeItem.appendChild(closeButton);
            sublist.appendChild(closeItem);

            button.addEventListener("click", function () {
                var open = button.getAttribute("aria-expanded") === "true";
                button.setAttribute("aria-expanded", open ? "false" : "true");
                setClassState(sublist, "is-open", !open);
            });

            item.appendChild(button);
            item.appendChild(sublist);
            list.appendChild(item);
        });

        panel.appendChild(heading);
        panel.appendChild(note);
        panel.appendChild(list);

        if (document.body.firstElementChild) {
            document.body.insertBefore(panel, document.body.firstElementChild.nextSibling);
        } else {
            document.body.appendChild(panel);
        }
    }

    function wireDrawer(toggle, drawerParts) {
        function setOpen(open) {
            toggle.setAttribute("aria-expanded", open ? "true" : "false");
            setClassState(drawerParts.backdrop, "is-open", open);
            setClassState(drawerParts.drawer, "is-open", open);
            setClassState(document.body, "site-menu-open", open);
        }

        toggle.addEventListener("click", function () {
            setOpen(toggle.getAttribute("aria-expanded") !== "true");
        });

        drawerParts.close.addEventListener("click", function () {
            setOpen(false);
        });

        drawerParts.backdrop.addEventListener("click", function () {
            setOpen(false);
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                setOpen(false);
            }
        });
    }

    function renderMenu() {
        if (!document.body || document.querySelector(".site-header")) {
            return;
        }

        ensureStyles();

        var headerParts = createHeader();
        var drawerParts = createDrawer();

        document.body.insertBefore(headerParts.header, document.body.firstChild);
        document.body.appendChild(drawerParts.backdrop);
        document.body.appendChild(drawerParts.drawer);

        if (!document.querySelector(".site-footer")) {
            document.body.appendChild(createFooter());
        }

        wireDrawer(headerParts.toggle, drawerParts);
        buildOfflineMenu();
        syncFooterYear();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", renderMenu);
    } else {
        renderMenu();
    }
})();
