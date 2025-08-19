// utils/confirmDialog.js
import Swal from 'sweetalert2'

/**
 * Show confirmation dialog with SweetAlert2
 * @param {string} actionText - What the user is confirming (e.g. "delete this order", "activate this stock")
 * @param {string} confirmText - Button text (e.g. "Yes, delete it!")
 * @returns {Promise<boolean>} true if confirmed, false if cancelled
 */
export async function confirmDialog(actionText, confirmText) {
    const result = await Swal.fire({
        title: `Are you sure you want to ${actionText}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#05C46B',
        cancelButtonColor: '#aaa',
        confirmButtonText: confirmText || `Yes, ${actionText}!`,
    })

    return result.isConfirmed
}
