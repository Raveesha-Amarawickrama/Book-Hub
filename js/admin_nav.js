// Get the button and sidebar elements
const toggleButton = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('adminSidebar');

// Add event listener for the button to toggle sidebar visibility
toggleButton.addEventListener('click', () => {
    // Toggle the 'hidden' class on the sidebar
    sidebar.classList.toggle('hidden');
});
