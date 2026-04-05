(function () {
    // WordPress環境ではmgoroVarsからURLを取得
    var homeUrl = (typeof mgoroVars !== "undefined" && mgoroVars.homeUrl)
        ? mgoroVars.homeUrl.replace(/\/+$/, "")
        : "";
    var currentPath = window.location.pathname || "";

    function setClassState(element, className, enabled) {
        if (!element) return;
        if (element.classList) {
            if (enabled) { element.classList.add(className); }
            else { element.classList.remove(className); }
            return;
        }
        var classes = element.className ? element.className.split(/\s+/) : [];
        var filtered = [];
        for (var i = 0; i < classes.length; i += 1) {
            if (classes[i] && classes[i] !== className) { filtered.push(classes[i]); }
        }
        if (enabled) { filtered.push(className); }
        element.className = filtered.join(" ");
    }

    function isCurrent(slug) {
        return currentPath.indexOf(slug) !== -1;
    }

    function createLink(label, href) {
        var link = document.createElement("a");
        link.className = "site-menu__link";
        link.href = href;
        link.textContent = label;
        if (isCurrent(href.replace(homeUrl, ""))) {
            setClassState(link, "is-current", true);
        }
        return link;
    }

    function createMenuItem(label, href) {
        var item = document.createElement("li");
        item.className = "site-menu__item";
        item.appendChild(createLink(label, href));
        return item;
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
            subItem.appendChild(createLink(entry.label, entry.href));
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
        brand.href = homeUrl + "/";
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
        close.textContent = "\u00d7";

        list.className = "site-menu__list";
        list.appendChild(createMenuItem("ホーム", homeUrl + "/"));

        // WordPressのメンテナンスカテゴリへのリンク
        list.appendChild(
            createSection("メンテナンス", [
                { label: "エンジン", href: homeUrl + "/maintenance-cat/エンジン/" },
                { label: "足回り", href: homeUrl + "/maintenance-cat/足回り/" },
                { label: "電装", href: homeUrl + "/maintenance-cat/電装/" },
                { label: "外装", href: homeUrl + "/maintenance-cat/外装/" },
                { label: "内装", href: homeUrl + "/maintenance-cat/内装/" },
                { label: "その他", href: homeUrl + "/maintenance-cat/その他/" }
            ])
        );
        list.appendChild(createMenuItem("オフラインMTG", homeUrl + "/offlinemeeting/"));

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
        text.appendChild(document.createTextNode(" \u00a9 MGORO.NET All Right Reserved."));
        footer.appendChild(text);

        return footer;
    }

    function syncFooterYear() {
        var years = document.querySelectorAll(".site-footer__year");
        var value = String(new Date().getFullYear());
        for (var i = 0; i < years.length; i += 1) {
            years[i].textContent = value;
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
        drawerParts.close.addEventListener("click", function () { setOpen(false); });
        drawerParts.backdrop.addEventListener("click", function () { setOpen(false); });
        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") { setOpen(false); }
        });
    }

    function renderMenu() {
        if (!document.body || document.querySelector(".site-header")) return;

        var headerParts = createHeader();
        var drawerParts = createDrawer();

        document.body.insertBefore(headerParts.header, document.body.firstChild);
        document.body.appendChild(drawerParts.backdrop);
        document.body.appendChild(drawerParts.drawer);

        if (!document.querySelector(".site-footer")) {
            document.body.appendChild(createFooter());
        }

        wireDrawer(headerParts.toggle, drawerParts);
        syncFooterYear();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", renderMenu);
    } else {
        renderMenu();
    }
})();
