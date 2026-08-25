// Service Worker Register
if ("serviceWorker" in navigator) {
    window.addEventListener("load", function () {
        navigator.serviceWorker
            .register("users/service-worker.js")
            .then((registration) => {
                //console.log('Service Worker is registered', registration);
            })
            .catch((err) => {
                console.error("Registration failed:", err);
            });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const roomCards = document.querySelectorAll("[data-hotel-room-card]");

    const formatRupiah = function (number) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
        }).format(number);
    };

    roomCards.forEach(function (card) {
        const basePrice = Number(card.dataset.roomPrice);
        const priceElement = card.querySelector("[data-hotel-room-price]");
        const quantityElement = card.querySelector(
            "[data-hotel-room-quantity]",
        );
        const decreaseButton = card.querySelector("[data-hotel-room-decrease]");
        const increaseButton = card.querySelector("[data-hotel-room-increase]");
        const favoriteButton = card.querySelector("[data-hotel-room-favorite]");

        let quantity = 1;

        const updateRoomPrice = function () {
            const totalPrice = basePrice * quantity;

            quantityElement.textContent = "x" + quantity;
            priceElement.textContent = formatRupiah(totalPrice);
            decreaseButton.disabled = quantity <= 1;
        };

        decreaseButton.addEventListener("click", function () {
            if (quantity > 1) {
                quantity--;
                updateRoomPrice();
            }
        });

        increaseButton.addEventListener("click", function () {
            if (quantity < 10) {
                quantity++;
                updateRoomPrice();
            }
        });

        favoriteButton.addEventListener("click", function () {
            const isActive = favoriteButton.classList.toggle("is-active");

            favoriteButton.setAttribute(
                "aria-pressed",
                isActive ? "true" : "false",
            );
        });

        updateRoomPrice();
    });
});
