<?php
require 'backend/ud/session.php'; // Load session data
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="icon"
      type="image/x-icon"
      href="assets/img/background/fav-logo.png"
    />
    <title>CodeCraft Dashboard - Test Section</title>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap");

      * {
        font-family: "Syne", serif;
        font-optical-sizing: auto;
        font-style: normal;
      }
      .relative-box {
        position: relative;
        bottom: 14rem;
      }
    </style>
  </head>
  <body
    class="bg-[url('Mask.png')] bg-cover bg-center bg-no-repeat min-h-screen font-sans"
  >
    <div class="flex">
      <!-- Sidebar -->
      <aside
        class="w-1/5 shadow-lg flex flex-col justify-between h-screen sticky top-0"
      >
        <div class="p-6">
          <!-- Logo -->
          <div class="text-2xl font-bold text-purple-600 mb-10">
            <img src="assets/img/background/logo.png" alt="Dashboard logo" />
          </div>

          <!-- Menu Links -->
          <nav class="space-y-6">
            <a
              href="dashboard.php"
              class="flex items-center space-x-4 text-purple-600"
            >
              <img
                src="assets/img/background/document-active.png"
                width="20px"
                alt="Dashboard Icon"
              />
              <span>Dashboard</span>
            </a>
            <a href="test-list.php" class="flex items-center space-x-4">
              <img
                src="assets/img/background/test.png"
                width="20px"
                alt="Tests Icon"
              />
              <span>Contests</span>
            </a>
            <a href="problems.php" class="flex items-center space-x-4">
              <img
                src="assets/img/background/document.png"
                width="20px"
                alt="Courses Icon"
              />
              <span>Problem Sets</span>
            </a>
            <a href="profile-coder.php" class="flex items-center space-x-4">
              <img
                src="assets/img/background/dp.png"
                width="20px"
                alt="Profile Icon"
              />
              <span>Profile</span>
            </a>
            <a href="leaderboard.php" class="flex items-center space-x-4">
              <img
                src="assets/img/background/leaderboard.png"
                width="20px"
                alt="Leaderboard Icon"
              />
              <span>Leaderboard</span>
            </a>
            <a href="inbox.php" class="flex items-center space-x-4">
              <img
                src="assets/img/background/chat.png"
                width="20px"
                alt="Chat Icon"
              />
              <span>Inbox</span>
            </a>
          </nav>
        </div>

        <div class="p-6 space-y-6">
          <!-- Bottom Links -->
          <a href="settings.php" class="flex items-center space-x-4">
            <img
              src="assets/img/background/setting.png"
              width="20px"
              alt="Settings Icon"
            />
            <span>Settings</span>
          </a>
          <a href="backend/logout.php" class="flex items-center space-x-4">
            <img
              src="assets/img/background/logout.png"
              width="20px"
              alt="Logout Icon"
            />
            <span>Log Out</span>
          </a>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
          <h1 class="text-2xl font-bold"></h1>
          <div class="flex items-center space-x-4">
            <div class="relative flex items-center">
              <img
                src="assets/img/background/search.png"
                alt="Search Icon"
                class="absolute left-3 w-4 h-4"
              />
              <input
                type="text"
                placeholder="Search"
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full"
              />
            </div>
            <img
              src="assets/img/background/Bell.png"
              width="20px"
              alt="Notification Icon"
              class="cursor-pointer"
            />
            <img
  src="<?php echo htmlspecialchars($userProfilePicture); ?>"
  alt="Profile Icon"
  class="rounded-full cursor-pointer"
  style="width: 40px; height: 40px; object-fit: cover"
/>


          </div>
        </header>

        <div class="grid grid-cols-2 gap-8">
  <!-- Left Column: Upcoming Contests -->
  <div class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-semibold text-purple-600 mb-4">Upcoming Coding Contests</h2>
    <div id="contests-container" class="grid grid-cols-1 gap-6">
      <!-- Contests will be dynamically added here -->
    </div>
  </div>

  <!-- Right Column: Leaderboard -->
  <div class="bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-xl font-semibold text-purple-600 mb-4">Leaderboard</h2>
    <div class="space-y-4">
      <!-- Dynamic leaderboard entries will be populated here -->
    </div>

    <!-- Pagination Controls -->
    <div class="flex justify-between mt-4">
      <button
        id="prevPage"
        class="w-10 h-10 px-2 py-2 bg-gray-300 text-gray-700 rounded-full flex justify-center disabled:opacity-50"
        disabled
      >
        <img src="assets/img/background/left.png" width="20px" alt="Previous Page" />
      </button>
      <span id="pagination-info" class="text-gray-700">Page 1 of 5</span>
      <button
        id="nextPage"
        class="w-10 h-10 px-2 py-2 bg-gray-300 text-gray-700 rounded-full flex justify-center disabled:opacity-50"
      >
        <img src="assets/img/background/right.png" width="20px" alt="Next Page" />
      </button>
    </div>
  </div>
</div>



      </main>
    </div>
  </body>


<script>
    let currentPage = 1; // Keep track of the current page

// Fetch and populate leaderboard
async function fetchLeaderboard(page = 1, search = "") {
  const url = `backend/fetch_leaderboard.php?page=${page}&search=${encodeURIComponent(search)}`;
  try {
    const response = await fetch(url);
    const data = await response.json();

    // Populate the leaderboard
    populateLeaderboard(data.data);

    // Update pagination info
    const totalPages = data.totalPages;
    document.querySelector("#pagination-info").textContent = `Page ${page} of ${totalPages}`;

    // Enable/disable pagination buttons based on the current page
    document.getElementById("prevPage").disabled = page <= 1;
    document.getElementById("nextPage").disabled = page >= totalPages;
  } catch (error) {
    console.error("Error fetching leaderboard data:", error);
    alert("Failed to load leaderboard. Please try again later.");
  }
}

// Populate leaderboard section dynamically
function populateLeaderboard(users) {
  const leaderboardContainer = document.querySelector(".space-y-4");
  leaderboardContainer.innerHTML = ""; // Clear existing content

  users.forEach((user, index) => {
    const rankClass =
      index === 0
        ? "bg-yellow-200"
        : index === 1
        ? "bg-gray-200"
        : index === 2
        ? "bg-orange-200"
        : "";

    const trophy =
      index === 0
        ? "assets/img/background/trophy-gold.png"
        : index === 1
        ? "assets/img/background/trophy-silver.png"
        : index === 2
        ? "assets/img/background/trophy-bronze.png"
        : null;

    const userHTML = `
      <div class="flex items-center justify-between ${rankClass} rounded-lg px-4 py-2">
        <div class="flex items-center space-x-4">
          <img src="${user.profilePicture || 'https://via.placeholder.com/40'}" alt="Avatar" class="w-10 h-10 rounded-full" />
          <span class="font-semibold">${index + 1}. ${user.username}</span>
        </div>
        <div class="flex items-center space-x-2">
    
          <span class="font-semibold text-purple-600">Solved ${user.total_problems_solved}</span>
        </div>
      </div>`;
    leaderboardContainer.innerHTML += userHTML;
  });
}

// Add event listeners for pagination buttons
document.getElementById("prevPage").addEventListener("click", () => {
  if (currentPage > 1) {
    currentPage--;
    fetchLeaderboard(currentPage);
  }
});

document.getElementById("nextPage").addEventListener("click", () => {
  currentPage++;
  fetchLeaderboard(currentPage);
});

// Initialize the leaderboard on page load
document.addEventListener("DOMContentLoaded", function () {
  fetchLeaderboard(currentPage);
});
</script>



    <script>
  document.addEventListener("DOMContentLoaded", function () {
    // Function to fetch contest data from the backend
    fetch("backend/contest/upcoming.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to fetch contest data.");
        }
        return response.json();
      })
      .then((contests) => {
        const container = document.getElementById("contests-container");
        container.innerHTML = ""; // Clear any existing content

        if (contests.length === 0) {
          container.innerHTML = `<p class="text-gray-500">No upcoming contests available.</p>`;
          return;
        }

        contests.forEach((contest) => {
          const contestCard = `
            <div class="flex flex-col items-center space-y-4 p-4 border border-gray-300 rounded-lg">
              <img
                src="${contest.banner || 'https://via.placeholder.com/80'}"
                alt="Contest Banner"
                class="rounded-lg mb-4"
              />
              <div>
                <h3 class="font-semibold">${contest.contest_name}</h3>
                <p class="text-gray-600 text-sm">Registration Ends: ${new Date(contest.registration_end_time).toLocaleString()}</p>
                <p class="text-sm text-gray-500">${contest.description}</p>
                <p class="font-semibold text-purple-600">Contest Dates: ${new Date(contest.start_time).toLocaleDateString()} - ${new Date(contest.end_time).toLocaleDateString()}</p>
              </div>
              <button
        class="mt-4 px-6 py-2 bg-purple-400 text-white rounded-full hover:bg-purple-500"
        onclick="window.location.href='upcoming-contest.php?contest_id=${contest.contest_id}'"
      >
        Details
      </button>
            </div>`;
          container.innerHTML += contestCard;
        });
      })
      .catch((error) => {
        console.error("Error:", error);
        const container = document.getElementById("contests-container");
        container.innerHTML = `<p class="text-red-500">Failed to load contests. Please try again later.</p>`;
      });
  });

  // Placeholder function for registering for contests
 
</script>

</html>
