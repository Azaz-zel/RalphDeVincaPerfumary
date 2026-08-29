// ===============================
// Filter Dropdown
// ===============================

function initDropdown(id, multiple = false) {

    const toggle = document.getElementById(`${id}-toggle`);
    const dropdown = document.getElementById(`${id}-dropdown`);
    const apply = document.getElementById(`${id}-apply`);
    const reset = document.getElementById(`${id}-reset`);
    const label = document.getElementById(`${id}-label`);
    const arrow = document.getElementById(`${id}-arrow`);

    if (!toggle || !dropdown) return;


    // ===============================
    // Open / Close Dropdown
    // ===============================

    toggle.addEventListener("click", (e) => {

        e.stopPropagation();

        // Tutup dropdown lain
        document.querySelectorAll(".dropdown").forEach(menu => {

            if (menu !== dropdown) {
                menu.classList.add("hidden");
            }

        });

        // Reset arrow dropdown lain
        document.querySelectorAll("[id$='-arrow']").forEach(icon => {

            if (icon !== arrow) {
                icon.classList.remove("rotate-180");
            }

        });

        // Toggle dropdown ini
        dropdown.classList.toggle("hidden");

        arrow?.classList.toggle("rotate-180");

    });


    // ===============================
    // Klik di luar dropdown
    // ===============================

    document.addEventListener("click", (e) => {

        if (
            !dropdown.contains(e.target) &&
            !toggle.contains(e.target)
        ) {

            dropdown.classList.add("hidden");

            arrow?.classList.remove("rotate-180");

        }

    });


    // ===============================
    // Apply Filter
    // ===============================

    apply?.addEventListener("click", () => {

        const checked =
            dropdown.querySelectorAll("input:checked");

        // ===============================
        // Sort AJAX
        // ===============================

        if (id === "sort") {

            const selected =
                checked.length > 0
                    ? checked[0].value
                    : "Most Popular";


            // Update label
            label.textContent = selected;


            const url =
                new URL(window.location.href);


            // Update sort
            url.searchParams.delete("sort");

            url.searchParams.set(
                "sort",
                selected
            );


            // Reset pagination
            url.searchParams.delete("page");


            // Update URL tanpa reload
            window.history.pushState(
                {},
                "",
                url.toString()
            );


            // AJAX request
            fetch(
                `/explore/filter?${url.searchParams.toString()}`,
                {
                    method: "GET",

                    headers: {
                        "X-Requested-With":
                            "XMLHttpRequest",

                        "Accept":
                            "application/json"
                    }
                }
            )

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        "Sort request failed."
                    );

                }

                return response.json();

            })

            .then(data => {

                // ===============================
                // Grid
                // ===============================

                const results =
                    document.getElementById(
                        "explore-results"
                    );

                if (results) {

                    results.innerHTML =
                        data.grid;

                }


                // ===============================
                // Pagination
                // ===============================

                const pagination =
                    document.getElementById(
                        "explore-pagination"
                    );

                if (pagination) {

                    pagination.innerHTML =
                        data.pagination;

                }


                // ===============================
                // Empty State
                // ===============================

                const empty =
                    document.getElementById(
                        "explore-empty"
                    );

                if (empty) {

                    if (data.total === 0) {

                        empty.classList.remove(
                            "hidden"
                        );

                    } else {

                        empty.classList.add(
                            "hidden"
                        );

                    }

                }

            })

            .catch(error => {

                console.error(
                    "Sort error:",
                    error
                );

            });


            // Tutup dropdown
            dropdown.classList.add(
                "hidden"
            );

            arrow?.classList.remove(
                "rotate-180"
            );


            return;
        }        


        // ===============================
        // Gender AJAX
        // ===============================

        if (id === "gender") {

            const selected =
                checked.length > 0
                    ? checked[0].value
                    : null;

            if (selected) {
                label.textContent = selected;
            } else {
                label.textContent = "Gender";
            }

            const url =
                new URL(window.location.href);

            // Hapus gender lama
            url.searchParams.delete("gender");

            // Tambahkan gender baru
            if (selected) {

                url.searchParams.set(
                    "gender",
                    selected
                );

            }

            // Reset pagination
            url.searchParams.delete("page");

            // Update URL tanpa reload
            window.history.pushState(
                {},
                "",
                url.toString()
            );

            // AJAX
            fetch(
                `/explore/filter?${url.searchParams.toString()}`,
                {
                    method: "GET",

                    headers: {
                        "X-Requested-With":
                            "XMLHttpRequest",

                        "Accept":
                            "application/json"
                    }
                }
            )
            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        "Gender filter request failed."
                    );

                }

                return response.json();

            })
            .then(data => {

                // Grid
                const results =
                    document.getElementById(
                        "explore-results"
                    );

                if (results) {

                    results.innerHTML =
                        data.grid;

                }

                // Pagination
                const pagination =
                    document.getElementById(
                        "explore-pagination"
                    );

                if (pagination) {

                    pagination.innerHTML =
                        data.pagination;

                }

                // Empty state
                const empty =
                    document.getElementById(
                        "explore-empty"
                    );

                if (empty) {

                    if (data.total === 0) {

                        empty.classList.remove(
                            "hidden"
                        );

                    } else {

                        empty.classList.add(
                            "hidden"
                        );

                    }

                }

            })
            .catch(error => {

                console.error(
                    "Gender filter error:",
                    error
                );

            });

            // Tutup dropdown
            dropdown.classList.add(
                "hidden"
            );

            arrow?.classList.remove(
                "rotate-180"
            );

            return;
        }

        // ===============================
        // Season AJAX
        // ===============================

        if (id === "season") {

            const selected =
                checked.length > 0
                    ? checked[0].value
                    : null;

            if (selected) {
                label.textContent = selected;
            } else {
                label.textContent = "Season";
            }

            const url =
                new URL(window.location.href);


            // Hapus season sebelumnya
            url.searchParams.delete("season");


            // Tambahkan season baru
            if (selected) {

                url.searchParams.set(
                    "season",
                    selected
                );

            }


            // Reset pagination
            url.searchParams.delete("page");


            // Update URL tanpa reload
            window.history.pushState(
                {},
                "",
                url.toString()
            );


            // Request AJAX
            fetch(
                `/explore/filter?${url.searchParams.toString()}`,
                {
                    method: "GET",

                    headers: {
                        "X-Requested-With":
                            "XMLHttpRequest",

                        "Accept":
                            "application/json"
                    }
                }
            )

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        "Season filter request failed."
                    );

                }

                return response.json();

            })

            .then(data => {

                // Grid
                const results =
                    document.getElementById(
                        "explore-results"
                    );

                if (results) {

                    results.innerHTML =
                        data.grid;

                }


                // Pagination
                const pagination =
                    document.getElementById(
                        "explore-pagination"
                    );

                if (pagination) {

                    pagination.innerHTML =
                        data.pagination;

                }


                // Empty state
                const empty =
                    document.getElementById(
                        "explore-empty"
                    );

                if (empty) {

                    if (data.total === 0) {

                        empty.classList.remove(
                            "hidden"
                        );

                    } else {

                        empty.classList.add(
                            "hidden"
                        );

                    }

                }

            })

            

            .catch(error => {

                console.error(
                    "Season filter error:",
                    error
                );

            });


            // Tutup dropdown
            dropdown.classList.add(
                "hidden"
            );

            arrow?.classList.remove(
                "rotate-180"
            );


            return;
        }


        // ===============================
        // Update Label
        // ===============================

        if (multiple) {

            if (checked.length === 0) {

                label.textContent = "Fragrance Family";

            } else {

                label.textContent =
                    `Fragrance Family (${checked.length})`;

            }

        } else {

            if (checked.length > 0) {

                label.textContent =
                    checked[0].value;

            }

        }


        // ===============================
        // FAMILY FILTER
        // ===============================

        if (id === "family") {

            const url =
                new URL(window.location.href);


            // Hapus family lama
            url.searchParams.delete("family[]");


            // Tambahkan family baru
            checked.forEach(input => {

                url.searchParams.append(
                    "family[]",
                    input.value
                );

            });


            // Reset pagination
            url.searchParams.delete("page");


            // ===============================
            // Update URL
            // Tanpa reload halaman
            // ===============================

            window.history.pushState(
                {},
                "",
                url.toString()
            );


            // ===============================
            // Request ke Laravel
            // ===============================

            const filterUrl =
                `/explore/filter?${url.searchParams.toString()}`;


            fetch(filterUrl, {

                method: "GET",

                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }

            })

            .then(response => {

                if (!response.ok) {

                    throw new Error(
                        "Filter request failed."
                    );

                }

                return response.json();

            })

            .then(data => {


                // ===============================
                // Update Grid
                // ===============================

                const results =
                    document.getElementById(
                        "explore-results"
                    );


                if (results) {

                    results.innerHTML =
                        data.grid;

                }


                // ===============================
                // Update Pagination
                // ===============================

                const pagination =
                    document.getElementById(
                        "explore-pagination"
                    );


                if (pagination) {

                    pagination.innerHTML =
                        data.pagination;

                }


                // ===============================
                // Update Empty State
                // ===============================

                const empty =
                    document.getElementById(
                        "explore-empty"
                    );


                if (empty) {

                    if (data.total === 0) {

                        empty.classList.remove(
                            "hidden"
                        );

                    } else {

                        empty.classList.add(
                            "hidden"
                        );

                    }

                }

            })

            .catch(error => {

                console.error(
                    "Explore filter error:",
                    error
                );

            });


            // ===============================
            // Tutup Dropdown
            // ===============================

            dropdown.classList.add("hidden");

            arrow?.classList.remove(
                "rotate-180"
            );


            return;

        }


        // ===============================
        // Filter biasa
        // ===============================

        dropdown.classList.add("hidden");

        arrow?.classList.remove(
            "rotate-180"
        );

    });


    // ===============================
    // Reset Dropdown
    // ===============================

    reset?.addEventListener("click", () => {

        // Uncheck semua
        dropdown
            .querySelectorAll("input")
            .forEach(input => {

                input.checked = false;

            });


        // Reset label
        switch (id) {

            case "family":

                label.textContent =
                    "Fragrance Family";

                break;


            case "season":

                label.textContent =
                    "Season";

                break;


            case "gender":

                label.textContent =
                    "Gender";

                break;


            case "sort":

                label.textContent =
                    "Sort By";

                break;

        }

    });

}


// ===============================
// Init Dropdown
// ===============================

initDropdown("family", true);

initDropdown("season");

initDropdown("gender");

initDropdown("sort");


// ===============================
// Filter Chips AJAX
// ===============================

document
    .querySelectorAll(".filter-chip")
    .forEach(chip => {

        chip.addEventListener("click", async () => {

            const filter = chip.dataset.filter;
            const value = chip.dataset.value;

            const url =
                new URL(window.location.href);


            // ===============================
            // Trending
            // ===============================

            if (filter === "trending") {

                const isActive =
                    url.searchParams.get("trending") === "1";

                if (isActive) {

                    url.searchParams.delete("trending");

                } else {

                    url.searchParams.set(
                        "trending",
                        "1"
                    );

                }

            }


            // ===============================
            // Designer / Niche
            // ===============================

            if (filter === "type") {

                const currentType =
                    url.searchParams.get("type");

                if (currentType === value) {

                    url.searchParams.delete("type");

                } else {

                    url.searchParams.set(
                        "type",
                        value
                    );

                }

            }


            // ===============================
            // Reset pagination
            // ===============================

            url.searchParams.delete("page");


            // ===============================
            // Update URL
            // Tanpa reload
            // ===============================

            window.history.pushState(
                {},
                "",
                url.toString()
            );


            // ===============================
            // AJAX Request
            // ===============================

            try {

                const response = await fetch(
                    `/explore/filter?${url.searchParams.toString()}`,
                    {
                        method: "GET",

                        headers: {
                            "X-Requested-With":
                                "XMLHttpRequest",

                            "Accept":
                                "application/json"
                        }
                    }
                );


                if (!response.ok) {

                    throw new Error(
                        "Filter request failed."
                    );

                }


                const data =
                    await response.json();


                // ===============================
                // Update Grid
                // ===============================

                const results =
                    document.getElementById(
                        "explore-results"
                    );

                if (results) {

                    results.innerHTML =
                        data.grid;

                }


                // ===============================
                // Update Pagination
                // ===============================

                const pagination =
                    document.getElementById(
                        "explore-pagination"
                    );

                if (pagination) {

                    pagination.innerHTML =
                        data.pagination;

                }


                // ===============================
                // Update Empty State
                // ===============================

                const empty =
                    document.getElementById(
                        "explore-empty"
                    );

                if (empty) {

                    if (data.total === 0) {

                        empty.classList.remove(
                            "hidden"
                        );

                    } else {

                        empty.classList.add(
                            "hidden"
                        );

                    }

                }


                // ===============================
                // Update Active State
                // ===============================

                updateChipStates();

            }

            catch (error) {

                console.error(
                    "Explore chip filter error:",
                    error
                );

            }

        });

    });


// ===============================
// Update Chip States
// ===============================

function updateChipStates() {

    const url =
        new URL(window.location.href);


    const trendingActive =
        url.searchParams.get("trending") === "1";

    const currentType =
        url.searchParams.get("type");


    document
        .querySelectorAll(".filter-chip")
        .forEach(chip => {

            const filter =
                chip.dataset.filter;

            const value =
                chip.dataset.value;

            let active = false;


            // Trending
            if (
                filter === "trending" &&
                trendingActive
            ) {

                active = true;

            }


            // Designer / Niche
            if (
                filter === "type" &&
                currentType === value
            ) {

                active = true;

            }


            // ===============================
            // Apply visual state
            // ===============================

            chip.classList.toggle(
                "bg-[#B08D57]",
                active
            );

            chip.classList.toggle(
                "border-[#B08D57]",
                active
            );

            chip.classList.toggle(
                "text-white",
                active
            );

        });

}


// ===============================
// Initial Chip State
// ===============================

updateChipStates();

// ===============================
// AJAX Pagination
// ===============================

document.addEventListener("click", async (e) => {

    const link = e.target.closest(
        "#explore-pagination a"
    );

    if (!link) return;

    e.preventDefault();

    const url = new URL(
        link.href,
        window.location.origin
    );

    // ===============================
    // Update URL
    // ===============================

    window.history.pushState(
        {},
        "",
        url.toString()
    );

    try {

        const response = await fetch(
            `/explore/filter?${url.searchParams.toString()}`,
            {
                method: "GET",

                headers: {
                    "X-Requested-With":
                        "XMLHttpRequest",

                    "Accept":
                        "application/json"
                }
            }
        );

        if (!response.ok) {

            throw new Error(
                "Pagination request failed."
            );

        }

        const data =
            await response.json();


        // ===============================
        // Update Grid
        // ===============================

        const results =
            document.getElementById(
                "explore-results"
            );

        if (results) {

            results.innerHTML =
                data.grid;

        }


        // ===============================
        // Update Pagination
        // ===============================

        const pagination =
            document.getElementById(
                "explore-pagination"
            );

        if (pagination) {

            pagination.innerHTML =
                data.pagination;

        }


        // ===============================
        // Update Empty State
        // ===============================

        const empty =
            document.getElementById(
                "explore-empty"
            );

        if (empty) {

            if (data.total === 0) {

                empty.classList.remove(
                    "hidden"
                );

            } else {

                empty.classList.add(
                    "hidden"
                );

            }

        }

    }

    catch (error) {

        console.error(
            "Pagination error:",
            error
        );

    }

});

/* ============================================================
   Generic AJAX filtering (Brands & Notes index pages)

   Any section marked with [data-filter-root] gets search,
   chip filters and pagination applied without a page reload,
   so the view never jumps back to the top.
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

    const root = document.querySelector("[data-filter-root]");

    if (!root) return;

    const endpoint = root.dataset.endpoint;
    const form = root.querySelector("[data-filter-form]");
    const searchInput = form?.querySelector("input[name='search']");
    const clearButton = root.querySelector("[data-clear-filters]");

    const results = document.querySelector("[data-filter-results]");
    const paginationBox = document.querySelector("[data-filter-pagination]");

    // Current filter state, seeded from the URL so a shared link still works.
    const state = new URLSearchParams(window.location.search);

    const activeFilterCount = () =>
        [...state.entries()].filter(([key, value]) => key !== "page" && value !== "").length;

    const syncClearButton = () => {
        if (!clearButton) return;
        clearButton.classList.toggle("hidden", activeFilterCount() === 0);
    };

    // Repaint chip styling so the active choice is obvious without a reload.
    const syncChips = () => {
        root.querySelectorAll("[data-filter]").forEach((chip) => {
            const key = chip.dataset.filter;
            const value = chip.dataset.value;
            const isActive = (state.get(key) || "") === value;

            // Two chip styles are in use: bordered (positions/types) and solid (families).
            if (chip.className.includes("border")) {
                chip.classList.toggle("border-[#B08D57]", isActive);
                chip.classList.toggle("bg-[#B08D57]", isActive);
                chip.classList.toggle("text-white", isActive);
                chip.classList.toggle("border-stone-300", !isActive);
                chip.classList.toggle("hover:border-[#B08D57]", !isActive);
                chip.classList.toggle("dark:border-stone-700", !isActive);
            } else {
                chip.classList.toggle("bg-stone-900", isActive);
                chip.classList.toggle("text-white", isActive);
                chip.classList.toggle("dark:bg-stone-100", isActive);
                chip.classList.toggle("dark:text-stone-900", isActive);
                chip.classList.toggle("bg-stone-100", !isActive);
                chip.classList.toggle("hover:bg-stone-200", !isActive);
                chip.classList.toggle("dark:bg-stone-800", !isActive);
                chip.classList.toggle("dark:hover:bg-stone-700", !isActive);
            }
        });
    };

    const setBusy = (busy) => {
        if (!results) return;
        results.style.opacity = busy ? "0.45" : "";
        results.style.transition = "opacity .2s";
    };

    const load = async () => {
        const query = state.toString();

        // Keep the address bar in sync without navigating.
        window.history.pushState({}, "", query ? `${endpoint}?${query}` : endpoint);

        setBusy(true);

        try {
            const response = await fetch(`${endpoint}?${query}`, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            });

            if (!response.ok) throw new Error("Filter request failed.");

            const data = await response.json();

            if (results && data.grid !== undefined) {
                results.innerHTML = data.grid;
            }

            if (paginationBox && data.pagination !== undefined) {
                paginationBox.innerHTML = data.pagination;
            }
        } catch (error) {
            // On failure fall back to a normal navigation so the user isn't stuck.
            window.location.href = `${endpoint}?${state.toString()}`;
            return;
        } finally {
            setBusy(false);
        }

        syncChips();
        syncClearButton();
    };

    const setParam = (key, value) => {
        if (value === "" || value === null) {
            state.delete(key);
        } else {
            state.set(key, value);
        }

        // Any filter change invalidates the current page number.
        state.delete("page");
    };

    // Chip filters
    root.querySelectorAll("[data-filter]").forEach((chip) => {
        chip.addEventListener("click", () => {
            setParam(chip.dataset.filter, chip.dataset.value);
            load();
        });
    });

    // Search
    form?.addEventListener("submit", (event) => {
        event.preventDefault();
        setParam("search", searchInput?.value.trim() ?? "");
        load();
    });

    // Clear
    clearButton?.addEventListener("click", () => {
        [...state.keys()].forEach((key) => state.delete(key));
        if (searchInput) searchInput.value = "";
        load();
    });

    // Pagination (delegated — the markup is replaced on every load)
    paginationBox?.addEventListener("click", (event) => {
        const link = event.target.closest("a[href]");
        if (!link) return;

        event.preventDefault();

        const page = new URL(link.href, window.location.origin).searchParams.get("page");
        if (!page) return;

        state.set("page", page);
        load();

        // Paging is the one case where returning to the top of the list helps.
        results?.scrollIntoView({ behavior: "smooth", block: "start" });
    });

    // Browser back/forward
    window.addEventListener("popstate", () => {
        const incoming = new URLSearchParams(window.location.search);
        [...state.keys()].forEach((key) => state.delete(key));
        incoming.forEach((value, key) => state.set(key, value));
        if (searchInput) searchInput.value = state.get("search") ?? "";
        load();
    });

});


/* ============================================================
   Mobile navigation toggle
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

    const button = document.getElementById("mobile-menu-button");
    const menu = document.getElementById("mobile-menu");
    const iconOpen = document.getElementById("mobile-menu-icon-open");
    const iconClose = document.getElementById("mobile-menu-icon-close");

    if (!button || !menu) return;

    const setOpen = (open) => {
        menu.classList.toggle("hidden", !open);
        iconOpen?.classList.toggle("hidden", open);
        iconClose?.classList.toggle("hidden", !open);
        button.setAttribute("aria-expanded", open ? "true" : "false");
        button.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    };

    button.addEventListener("click", () => {
        setOpen(menu.classList.contains("hidden"));
    });

    // Close when the viewport grows past the mobile breakpoint.
    window.addEventListener("resize", () => {
        if (window.innerWidth >= 1024) setOpen(false);
    });

    // Close on Escape.
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") setOpen(false);
    });

});


/* ============================================================
   Explore — Clear All Filters

   Previously this listener was registered *inside* the dropdown
   "Apply" handler, so the button did nothing until a dropdown
   filter had been applied first (e.g. arriving from the home
   search), and stacked duplicate listeners on repeat use.
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {

    const clearFilters = document.getElementById("clear-filters");

    if (!clearFilters) return;

    clearFilters.addEventListener("click", async (event) => {

        event.preventDefault();

        // Drop every filter param, including the indexed family[0], family[1]…
        // entries that the old handler missed by only deleting "family[]".
        const url = new URL(window.location.href);
        [...url.searchParams.keys()].forEach((key) => url.searchParams.delete(key));

        window.history.pushState({}, "", url.pathname);

        try {
            const response = await fetch("/explore/filter", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            });

            if (!response.ok) throw new Error("Clear filters request failed.");

            const data = await response.json();

            const results = document.getElementById("explore-results");
            if (results) results.innerHTML = data.grid;

            const pagination = document.getElementById("explore-pagination");
            if (pagination) pagination.innerHTML = data.pagination;

            const empty = document.getElementById("explore-empty");
            if (empty) empty.classList.toggle("hidden", data.total !== 0);

            // Reset dropdown checkboxes and their labels.
            document.querySelectorAll(".dropdown").forEach((dropdown) => {
                dropdown.querySelectorAll("input").forEach((input) => {
                    input.checked = false;
                });
            });

            const labels = {
                "family-label": "Fragrance Family",
                "season-label": "Season",
                "gender-label": "Gender",
                "sort-label": "Sort By",
            };

            Object.entries(labels).forEach(([id, text]) => {
                const el = document.getElementById(id);
                if (el) el.textContent = text;
            });

            // Reset quick-filter chips.
            document.querySelectorAll(".filter-chip").forEach((chip) => {
                chip.classList.remove("bg-[#B08D57]", "border-[#B08D57]", "text-white");
            });

            // Reset the search box and its "results for …" indicator.
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) searchInput.value = "";

            document.getElementById("search-indicator")?.classList.add("hidden");

            // The inline "Clear" link next to Search is server-rendered only
            // when a search is active, so remove it once filters are cleared.
            document.getElementById("clear-search-link")?.remove();

        } catch (error) {
            // Fall back to a real navigation so the user is never stuck.
            window.location.href = "/explore";
        }

    });

});
