//sample hindi ko lang napagana

const form = document.getElementById('registration-form');
const currentPassword = document.getElementById('current-password');
const NewPassword = document.getElementById('new-password');
const ConfirmPassword = document.getElementById('re-enter-password');

form.addEventListener('submit', e => {
    e.preventDefault();

    validateInputs();
});

const setError = (element, message) => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success');
}

const setSuccess = element => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
}

if (currentPassword.value.trim() === '') {
    setError(currentPassword, 'Current password is required');

}

