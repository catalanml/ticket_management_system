document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let isValid = true;

    const form = this;

    const inputs = document.querySelectorAll('#registerForm [required]');
    inputs.forEach(input => {
        const invalidFeedback = input.nextElementSibling;
        if (!input.value.trim()) {
            invalidFeedback.style.display = 'block';
            isValid = false;
        } else {
            invalidFeedback.style.display = 'none';
        }
    });

    if (isValid) {
        HTMLFormElement.prototype.submit.call(form);
    }

});
