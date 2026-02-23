this.$js.toggleStep = (event, step) => {
    let el = event.currentTarget;
    let stepElement = el.closest('[data-onboarding-step]');
    if (stepElement.hasAttribute('data-completed')) {
        stepElement.removeAttribute('data-completed');
    } else {
        stepElement.setAttribute('data-completed', '');
    }
    this.toggleStep(step)
}
