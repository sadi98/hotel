document.addEventListener("DOMContentLoaded", function () {
    initializeBootstrapValidation();
    initializePasswordToggles();
    initializePasswordStrength();
    initializePasswordConfirmation();
    initializeOtpInputs();
    initializeOtpTimer();
    initializeSubmitLoading();
});

/**
 * Initialize Bootstrap form validation.
 */
function initializeBootstrapValidation() {
    const forms = document.querySelectorAll(".needs-validation");

    forms.forEach(function (form) {
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                const firstInvalidField = form.querySelector(":invalid");

                if (firstInvalidField) {
                    firstInvalidField.focus();
                }
            }

            form.classList.add("was-validated");
        });
    });
}

/**
 * Show or hide password values.
 */
function initializePasswordToggles() {
    const buttons = document.querySelectorAll("[data-password-target]");

    buttons.forEach(function (button) {
        button.addEventListener("click", function () {
            const targetId = button.dataset.passwordTarget;
            const input = document.getElementById(targetId);
            const icon = button.querySelector("i");

            if (!input || !icon) {
                return;
            }

            const passwordIsVisible = input.type === "text";

            input.type = passwordIsVisible ? "password" : "text";

            icon.classList.toggle("bi-eye", passwordIsVisible);

            icon.classList.toggle("bi-eye-slash", !passwordIsVisible);

            button.setAttribute(
                "aria-label",
                passwordIsVisible ? "Show password" : "Hide password",
            );
        });
    });
}

/**
 * Display the password strength indicator.
 */
function initializePasswordStrength() {
    const passwordInput = document.getElementById("password");

    const strengthBars = document.querySelectorAll(
        ".password-strength-bars span",
    );

    const strengthText = document.getElementById("passwordStrengthText");

    if (!passwordInput || !strengthBars.length || !strengthText) {
        return;
    }

    passwordInput.addEventListener("input", function () {
        const password = passwordInput.value;
        let score = 0;

        if (password.length >= 8) {
            score++;
        }

        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            score++;
        }

        if (/\d/.test(password)) {
            score++;
        }

        if (/[^A-Za-z0-9]/.test(password)) {
            score++;
        }

        const strengthLevels = {
            0: {
                text: "Use at least 8 characters.",
                color: "#8a98aa",
            },
            1: {
                text: "Your password is weak.",
                color: "#dc3545",
            },
            2: {
                text: "Your password has medium strength.",
                color: "#f59e0b",
            },
            3: {
                text: "Your password is strong.",
                color: "#1da1d2",
            },
            4: {
                text: "Your password is very strong.",
                color: "#159b68",
            },
        };

        strengthBars.forEach(function (bar, index) {
            bar.style.backgroundColor =
                index < score ? strengthLevels[score].color : "#e1eaf4";
        });

        strengthText.textContent = strengthLevels[score].text;

        strengthText.style.color = strengthLevels[score].color;
    });
}

/**
 * Check whether the password confirmation matches.
 */
function initializePasswordConfirmation() {
    const passwordInput = document.getElementById("password");

    const confirmationInput = document.getElementById("password_confirmation");

    const message = document.getElementById("passwordMatchMessage");

    if (!passwordInput || !confirmationInput || !message) {
        return;
    }

    function checkPasswordMatch() {
        if (!confirmationInput.value) {
            message.textContent = "";
            message.className = "password-match-message";

            confirmationInput.setCustomValidity("");

            return;
        }

        if (passwordInput.value === confirmationInput.value) {
            message.textContent = "Passwords match.";

            message.className = "password-match-message success";

            confirmationInput.setCustomValidity("");
        } else {
            message.textContent = "The password confirmation does not match.";

            message.className = "password-match-message error";

            confirmationInput.setCustomValidity(
                "The password confirmation does not match.",
            );
        }
    }

    passwordInput.addEventListener("input", checkPasswordMatch);

    confirmationInput.addEventListener("input", checkPasswordMatch);
}

/**
 * Initialize the six-digit OTP inputs.
 */
function initializeOtpInputs() {
    const otpForm = document.getElementById("otpForm");
    const otpHiddenInput = document.getElementById("otpValue");

    const otpInputs = Array.from(document.querySelectorAll(".otp-input"));

    const otpClientError = document.getElementById("otpClientError");

    if (!otpForm || !otpHiddenInput || !otpInputs.length) {
        return;
    }

    const oldOtp = otpHiddenInput.value.trim();

    if (/^\d{6}$/.test(oldOtp)) {
        otpInputs.forEach(function (input, index) {
            input.value = oldOtp[index];
            input.classList.add("filled");
        });
    }

    function updateOtpValue() {
        otpHiddenInput.value = otpInputs
            .map(function (input) {
                return input.value;
            })
            .join("");
    }

    otpInputs.forEach(function (input, index) {
        input.addEventListener("input", function () {
            input.value = input.value.replace(/\D/g, "").slice(-1);

            input.classList.toggle("filled", input.value.length === 1);

            if (input.value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
                otpInputs[index + 1].select();
            }

            updateOtpValue();

            if (otpClientError && otpHiddenInput.value.length === 6) {
                otpClientError.classList.add("d-none");
            }
        });

        input.addEventListener("keydown", function (event) {
            if (event.key === "Backspace" && !input.value && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = "";

                otpInputs[index - 1].classList.remove("filled");

                updateOtpValue();
            }

            if (event.key === "ArrowLeft" && index > 0) {
                event.preventDefault();
                otpInputs[index - 1].focus();
            }

            if (event.key === "ArrowRight" && index < otpInputs.length - 1) {
                event.preventDefault();
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener("paste", function (event) {
            event.preventDefault();

            const clipboard = event.clipboardData || window.clipboardData;

            const pastedValue = clipboard
                .getData("text")
                .replace(/\D/g, "")
                .slice(0, 6);

            if (!pastedValue) {
                return;
            }

            otpInputs.forEach(function (otpInput, otpIndex) {
                otpInput.value = pastedValue[otpIndex] || "";

                otpInput.classList.toggle("filled", Boolean(otpInput.value));
            });

            updateOtpValue();

            const nextIndex = Math.min(
                pastedValue.length,
                otpInputs.length - 1,
            );

            otpInputs[nextIndex].focus();
        });
    });

    otpForm.addEventListener("submit", function (event) {
        updateOtpValue();

        if (!/^\d{6}$/.test(otpHiddenInput.value)) {
            event.preventDefault();
            event.stopPropagation();

            if (otpClientError) {
                otpClientError.classList.remove("d-none");
            }

            const emptyInput = otpInputs.find(function (input) {
                return !input.value;
            });

            (emptyInput || otpInputs[0]).focus();
        }
    });
}

/**
 * Initialize the OTP expiration timer.
 */
function initializeOtpTimer() {
    const timerElement = document.getElementById("otpTimer");

    const resendButton = document.getElementById("resendOtpButton");

    if (!timerElement || !resendButton) {
        return;
    }

    let remainingSeconds = Number(timerElement.dataset.duration) || 300;

    let timerInterval = null;

    function updateTimer() {
        const minutes = Math.floor(remainingSeconds / 60);

        const seconds = remainingSeconds % 60;

        timerElement.textContent =
            String(minutes).padStart(2, "0") +
            ":" +
            String(seconds).padStart(2, "0");

        if (remainingSeconds <= 0) {
            timerElement.textContent = "Expired";
            timerElement.classList.add("expired");

            resendButton.disabled = false;

            if (timerInterval) {
                clearInterval(timerInterval);
            }

            return;
        }

        remainingSeconds--;
    }

    updateTimer();

    timerInterval = setInterval(updateTimer, 1000);
}

/**
 * Display a loading state when a valid form is submitted.
 */
function initializeSubmitLoading() {
    const forms = document.querySelectorAll(".auth-form");

    forms.forEach(function (form) {
        form.addEventListener("submit", function () {
            if (!form.checkValidity()) {
                return;
            }

            if (form.id === "otpForm") {
                const otpValue = document.getElementById("otpValue");

                if (!otpValue || !/^\d{6}$/.test(otpValue.value)) {
                    return;
                }
            }

            const button = form.querySelector(
                'button[type="submit"].auth-primary-btn',
            );

            if (!button) {
                return;
            }

            button.classList.add("loading");
            button.disabled = true;

            button.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm"
                    aria-hidden="true"
                ></span>
                <span>Processing...</span>
            `;
        });
    });
}
