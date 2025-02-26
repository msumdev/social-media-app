<template>
    <div class="sidebar scrollbar">
        <div class="sidebar-top text-center position-relative pb_30">
            <Link :href="'/u/' + user.username" class="profile-img profile-img_100">
                <img :src="user.profile_picture" class="profile_icon" alt="admin_img">
            </Link>
            <h2 class="title16">{{ user.first_name }}</h2>
            <Link :href="'/u/' + user.username" class="text-dark-grey text-sm id-link d-block">@{{ user.username }}</Link>
			<Link :href="'/u/' + user.username" class="text-dark-grey text-sm id-link d-block">{{ user.age }} {{ user.sex?.code }} {{ user.country?.code }}</Link>
        </div>

        <div class="sidebar-links pb_35">
            <h2 class="pb_16 title16">Main Menu</h2>
            <div class="menu-link-wrap pl_8 pr_8">
                <Link href="/" class="sidebar-link d-flex align-items-center gap_x_16 active">
                    <svg class="icon24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M2.39999 13.713C2.39999 8.082 3.01399 8.475 6.31899 5.41C7.76499 4.246 10.015 2 11.958 2C13.9 2 16.195 4.235 17.654 5.41C20.959 8.475 21.572 8.082 21.572 13.713C21.572 22 19.613 22 11.986 22C4.35899 22 2.39999 22 2.39999 13.713Z"
                              stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path class="path_white" d="M9.0787 16.1353H14.8937" stroke="#3B3B3B" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <p>Home page</p>
                </Link>
            </div>
            <div class="menu-link-wrap pl_8 pr_8">
                <Link href="/chat" class="sidebar-link d-flex align-items-center gap_x_16">
                    <svg class="icon24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M21.37 13.61C20.72 17.55 17.79 20.58 13.87 21.32C11.72 21.73 9.67997 21.41 7.91997 20.58C7.62997 20.44 7.15996 20.38 6.84996 20.45C6.18996 20.61 5.08 20.88 4.14 21.1C3.24 21.32 2.68001 20.76 2.90001 19.86L3.54997 17.16C3.62997 16.85 3.55997 16.37 3.41997 16.08C2.61997 14.4 2.29999 12.45 2.62999 10.4C3.26999 6.45999 6.45 3.26999 10.39 2.61999C16.89 1.56999 22.43 7.10999 21.37 13.61Z"
                            stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path class="path_white" d="M14.09 6.57999C15.63 7.17999 16.85 8.40999 17.43 9.95999" stroke="#3B3B3B"
                              stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>

                    <p>Chat</p>
                </Link>
            </div>
            <div class="menu-link-wrap pl_8 pr_8">
                <a href="#" class="sidebar-link d-flex align-items-center gap_x_16">

                    <svg class="icon24 fill_full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2071_1051)">
                            <path
                                d="M14.68 8.4C14.68 9.46 14.38 10.45 13.83 11.29C13.79 11.37 13.74 11.44 13.69 11.51C12.73 12.89 11.12 13.8 9.32001 13.8C6.36001 13.8 3.95001 11.38 3.95001 8.4C3.95001 5.42 6.36001 3 9.32001 3C11.13 3 12.74 3.91 13.69 5.29C13.74 5.36 13.79 5.43 13.83 5.52C14.38 6.36 14.68 7.35 14.68 8.41V8.4Z"
                                stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M20.5 8.40001C20.5 10.6 18.73 12.38 16.55 12.38C15.5 12.38 14.55 11.97 13.84 11.29C14.39 10.45 14.69 9.46001 14.69 8.40001C14.69 7.34001 14.39 6.35001 13.84 5.51001C14.55 4.83001 15.5 4.42001 16.55 4.42001C18.73 4.42001 20.5 6.20001 20.5 8.40001Z"
                                stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />

                            <path d="M3.5 21C6.11 16.58 12.46 16.56 15.1 20.95L15.13 21" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />

                            <path d="M19.16 20.1C18.74 18.64 17.74 17.5 16.48 17.01L17.67 15.82" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                            <clipPath id="clip0_2071_1051">
                                <rect width="24" height="24" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>

                    <p>Friends</p>
                </a>
            </div>
            <div class="menu-link-wrap pl_8 pr_8">
                <a href="#" class="sidebar-link d-flex align-items-center gap_x_16">

                    <svg class="icon24 fill_full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="path_white"
                              d="M19.11 20.6C17.14 19.71 15.31 18.53 13.68 17.1L13.42 16.9C11.37 15.26 9.57999 13.85 7.73999 13.98C5.95999 14.27 4.31 15.1 3 16.35"
                              stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                        <path d="M13.67 17.1C14.65 16 15.97 15.25 17.42 14.97C18.56 14.89 19.68 15.73 20.95 16.74"
                              stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                        <path class="path_white"
                              d="M14.97 10.55C16.08 10.55 16.97 9.64999 16.97 8.54999C16.97 7.44999 16.07 6.54999 14.97 6.54999C13.87 6.54999 12.97 7.44999 12.97 8.54999C12.97 9.64999 13.87 10.55 14.97 10.55Z"
                              stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />
                        <path class="ff"
                              d="M17.77 3H6.22998C4.44998 3 3 4.45001 3 6.23001V17.77C3 19.55 4.44998 21 6.22998 21H17.77C19.55 21 21 19.55 21 17.77V6.23001C21 4.45001 19.55 3 17.77 3Z"
                              stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" />

                    </svg>


                    <p>Gallery</p>
                </a>
            </div>
            <div class="menu-link-wrap pl_8 pr_8">
                <a href="#" class="sidebar-link d-flex align-items-center gap_x_16">

                    <svg class="icon24 " viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2071_1063)">
                            <path
                                d="M16 5.70001H8C5.79086 5.70001 4 7.49087 4 9.70001V17C4 19.2092 5.79086 21 8 21H16C18.2091 21 20 19.2092 20 17V9.70001C20 7.49087 18.2091 5.70001 16 5.70001Z"
                                stroke="#3B3B3B" stroke-width="1.5" stroke-linejoin="round" />
                            <path d="M8.40002 3V5.7" stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round" />
                            <path d="M15.6 3V5.7" stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round" />
                            <path class="path_white" d="M20.1 10.2H3.90002" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linejoin="round" />
                            <path class="path_white" d="M8.28998 13.8H8.51001" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                            <path class="path_white" d="M11.32 13.8H12.68" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                            <path class="path_white" d="M15.49 13.8H15.71" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                            <path class="path_white" d="M8.28998 17.4H9.90997" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                            <path class="path_white" d="M12.9 17.4H12.68" stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round" />
                            <path class="path_white" d="M15.49 17.4H15.71" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                            <clipPath id="clip0_2071_1063">
                                <rect width="24" height="24" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>


                    <p>Events</p>
                </a>
            </div>
            <div class="menu-link-wrap pl_8 pr_8">
                <a href="#" class="sidebar-link d-flex align-items-center gap_x_16">


                    <svg class="icon24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_2071_1069)">
                            <path
                                d="M15.2 3H7C4.79086 3 3 4.79086 3 7V17C3 19.2091 4.79086 21 7 21H15.2C17.4092 21 19.2 19.2091 19.2 17V7C19.2 4.79086 17.4092 3 15.2 3Z"
                                stroke="#3B3B3B" stroke-width="1.5" stroke-linejoin="round" />
                            <path class="path_white" d="M10.56 12.9H13.8" stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round" />
                            <path class="path_white" d="M8.40002 16.5H13.8" stroke="#3B3B3B" stroke-width="1.5"
                                  stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M21 7.95H19.2" stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round" />
                            <path d="M21 16.05H19.2" stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round"
                                  stroke-linejoin="round" />
                        </g>
                        <defs>
                            <clipPath id="clip0_2071_1069">
                                <rect width="24" height="24" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>


                    <p>Blog</p>
                </a>
            </div>
        </div>

        <div class="sidebar-links">
            <h2 class="pb_16 title16">Account</h2>
            <div class="menu-link-wrap pl_8 pr_8">
                <a href="profile.html" class="sidebar-link d-flex align-items-center gap_x_16">

                    <svg class="icon24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="path_white"
                              d="M12.1202 12.78C12.0502 12.77 11.9602 12.77 11.8802 12.78C10.1202 12.72 8.72021 11.28 8.72021 9.51C8.72021 7.7 10.1802 6.23 12.0002 6.23C13.8102 6.23 15.2802 7.7 15.2802 9.51C15.2702 11.28 13.8802 12.72 12.1202 12.78Z"
                              stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path class="path_white"
                              d="M18.7398 19.38C16.9598 21.01 14.5998 22 11.9998 22C9.39977 22 7.03977 21.01 5.25977 19.38C5.35977 18.44 5.95977 17.52 7.02977 16.8C9.76977 14.98 14.2498 14.98 16.9698 16.8C18.0398 17.52 18.6398 18.44 18.7398 19.38Z"
                              stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                            stroke="#3B3B3B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>


                    <p>Profile</p>
                </a>
            </div>
            <div class="menu-link-wrap pl_8 pr_8">
                <a href="#" class="sidebar-link d-flex align-items-center gap_x_16">


                    <svg class="icon24 fill_full" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="path_white"
                              d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                              stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                              stroke-linejoin="round" />
                        <path
                            d="M2 12.88V11.12C2 10.08 2.85 9.22 3.9 9.22C5.71 9.22 6.45 7.94 5.54 6.37C5.02 5.47 5.33 4.3 6.24 3.78L7.97 2.79C8.76 2.32 9.78 2.6 10.25 3.39L10.36 3.58C11.26 5.15 12.74 5.15 13.65 3.58L13.76 3.39C14.23 2.6 15.25 2.32 16.04 2.79L17.77 3.78C18.68 4.3 18.99 5.47 18.47 6.37C17.56 7.94 18.3 9.22 20.11 9.22C21.15 9.22 22.01 10.07 22.01 11.12V12.88C22.01 13.92 21.16 14.78 20.11 14.78C18.3 14.78 17.56 16.06 18.47 17.63C18.99 18.54 18.68 19.7 17.77 20.22L16.04 21.21C15.25 21.68 14.23 21.4 13.76 20.61L13.65 20.42C12.75 18.85 11.27 18.85 10.36 20.42L10.25 20.61C9.78 21.4 8.76 21.68 7.97 21.21L6.24 20.22C5.33 19.7 5.02 18.53 5.54 17.63C6.45 16.06 5.71 14.78 3.9 14.78C2.85 14.78 2 13.92 2 12.88Z"
                            stroke="#3B3B3B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>


                    <p>Settings</p>
                </a>
            </div>
            <div class="menu-link-wrap pl_8 pr_8">
                <a href="/logout" class="sidebar-link logout_link d-flex align-items-center gap_x_16">


                    <svg class="icon24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.24 22.27H15.11C10.67 22.27 8.53002 20.52 8.16002 16.6C8.12002 16.19 8.42002 15.82 8.84002 15.78C9.24002 15.74 9.62002 16.05 9.66002 16.46C9.95002 19.6 11.43 20.77 15.12 20.77H15.25C19.32 20.77 20.76 19.33 20.76 15.26V8.74C20.76 4.67 19.32 3.23 15.25 3.23H15.12C11.41 3.23 9.93002 4.42 9.66002 7.62C9.61002 8.03 9.26002 8.34 8.84002 8.3C8.42002 8.27 8.12001 7.9 8.15001 7.49C8.49001 3.51 10.64 1.73 15.11 1.73H15.24C20.15 1.73 22.25 3.83 22.25 8.74V15.26C22.25 20.17 20.15 22.27 15.24 22.27Z"
                            fill="#F34610" />
                        <path
                            d="M15.0001 12.75H3.62012C3.21012 12.75 2.87012 12.41 2.87012 12C2.87012 11.59 3.21012 11.25 3.62012 11.25H15.0001C15.4101 11.25 15.7501 11.59 15.7501 12C15.7501 12.41 15.4101 12.75 15.0001 12.75Z"
                            fill="#F34610" />
                        <path
                            d="M5.84994 16.1C5.65994 16.1 5.46994 16.03 5.31994 15.88L1.96994 12.53C1.67994 12.24 1.67994 11.76 1.96994 11.47L5.31994 8.12C5.60994 7.83 6.08994 7.83 6.37994 8.12C6.66994 8.41 6.66994 8.89 6.37994 9.18L3.55994 12L6.37994 14.82C6.66994 15.11 6.66994 15.59 6.37994 15.88C6.23994 16.03 6.03994 16.1 5.84994 16.1Z"
                            fill="#F34610" />
                    </svg>

                    <p>Log Out</p>
                </a>
            </div>
        </div>
    </div>

    <button type="button" class="expand-btn d-lg-none d-flex align-items-center justify-content-center mr_10">
        <img src="/images/expand.svg" class="image-black icon28" alt="hamburger">
        <img src="/images/close.svg" class="image-black icon28" alt="Close_2">
    </button>
</template>
<script>
import {Link} from "@inertiajs/vue3";

export default {
	components: {
		Link
	},
	props: {
		user: Object
	}
}
</script>
