// utils/deleteConfirm.js
import Swal from 'sweetalert2'

/**
 * Delete confirmation dialog
 * @param {string} itemName - name of the item (optional, e.g. "this order")
 * @returns {Promise<boolean>} true if confirmed, false if cancelled
 */
export async function deleteConfirm(itemName = 'this item') {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: `You won't be able to revert deleting ${itemName}!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#D62925', // red
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, delete it!',
    })

    return result.isConfirmed
}
