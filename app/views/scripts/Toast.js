/**
 * Toast Notification System
 * Handles creation, display, and management of toast notifications
 */

class ToastManager {
  constructor() {
    this.container = null;
    this.template = null;
    this.toasts = [];
    this.maxToasts = 3;
    this.init();
  }

  init() {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", () =>
        this.setupToastSystem(),
      );
    } else {
      this.setupToastSystem();
    }
  }

  setupToastSystem() {
    this.container = document.getElementById("toast-container");
    this.template = document.getElementById("toast-template");

    if (!this.container || !this.template) {
      console.warn("Toast system: Container or template not found");
      return;
    }
  }

  /**
   * Show a toast notification
   * @param {string} message - The message to display
   * @param {string} type - Type of toast (success, error, info, warning)
   * @param {number} duration - Duration in milliseconds (default: 5000)
   */
  show(message, type = "info", duration = 1000) {
    if (!this.container || !this.template) {
      console.warn("Toast system not initialized");
      return;
    }

    if (this.toasts.length >= this.maxToasts) {
      this.close(this.toasts[0].element);
    }

    const toast = this.template.cloneNode(true);
    toast.id = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
    toast.style.display = "block";

    const messageElement = toast.querySelector(".toast-message");
    messageElement.textContent = message;

    toast.classList.add(`toast-${type}`);

    const progressFill = toast.querySelector(".toast-progress-fill");
    progressFill.style.animationDuration = `${duration}ms`;

    this.container.appendChild(toast);

    const toastObj = {
      element: toast,
      type: type,
      duration: duration,
      timeoutId: null,
      progressTimeoutId: null,
    };

    this.toasts.push(toastObj);

    requestAnimationFrame(() => {
      toast.classList.add("toast-show");

      requestAnimationFrame(() => {
        progressFill.classList.add("animate");
      });
    });

    toastObj.timeoutId = setTimeout(() => {
      this.close(toast);
    }, duration);

    return toastObj;
  }

  /**
   * Close a specific toast
   * @param {HTMLElement} toastElement - The toast element to close
   */
  close(toastElement) {
    const toastIndex = this.toasts.findIndex((t) => t.element === toastElement);
    if (toastIndex === -1) return;

    const toastObj = this.toasts[toastIndex];

    if (toastObj.timeoutId) {
      clearTimeout(toastObj.timeoutId);
    }
    if (toastObj.progressTimeoutId) {
      clearTimeout(toastObj.progressTimeoutId);
    }

    toastElement.classList.add("toast-hide");
    toastElement.classList.remove("toast-show");

    setTimeout(() => {
      if (toastElement.parentNode) {
        toastElement.parentNode.removeChild(toastElement);
      }
      this.toasts.splice(toastIndex, 1);
    }, 300);
  }

  /**
   * Close all toasts
   */
  closeAll() {
    [...this.toasts].forEach((toast) => {
      this.close(toast.element);
    });
  }

  /**
   * Show success toast
   * @param {string} message
   * @param {number} duration
   */
  success(message, duration = 5000) {
    return this.show(message, "success", duration);
  }

  /**
   * Show error toast
   * @param {string} message
   * @param {number} duration
   */
  error(message, duration = 5000) {
    return this.show(message, "error", duration);
  }

  /**
   * Show info toast
   * @param {string} message
   * @param {number} duration
   */
  info(message, duration = 5000) {
    return this.show(message, "info", duration);
  }

  /**
   * Show warning toast
   * @param {string} message
   * @param {number} duration
   */
  warning(message, duration = 6000) {
    return this.show(message, "warning", duration);
  }
}

const toast = new ToastManager();

function closeToast(button) {
  const toastElement = button.closest(".toast-notification");
  if (toastElement && toast) {
    toast.close(toastElement);
  }
}

window.toast = toast;
window.closeToast = closeToast;

// Function to check and show server messages (only once)
let serverMessagesProcessed = false;
function checkAndShowServerMessages() {
  if (serverMessagesProcessed || typeof window.serverMessages === "undefined") {
    return;
  }
  
  serverMessagesProcessed = true;
  
  if (window.serverMessages.success) {
    toast.success(window.serverMessages.success);
  }
  if (window.serverMessages.error) {
    toast.error(window.serverMessages.error);
  }
  if (window.serverMessages.info) {
    toast.info(window.serverMessages.info);
  }
  if (window.serverMessages.warning) {
    toast.warning(window.serverMessages.warning);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  // Check immediately
  checkAndShowServerMessages();
  
  // Also check after a short delay in case serverMessages is set after DOM ready
  setTimeout(checkAndShowServerMessages, 100);
});
