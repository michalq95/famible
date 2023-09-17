<template>
    <div class="container mx-auto">
        <div class="px-4 md:block">
            <div class="pb-8 mt-auto">
                <div class="text-center">
                    <h1
                        class="text-2xl md:text-3xl mb-8 mt-16 font-serif max-w-3xl mx-auto"
                    >
                        The Progressive Python
                        <span class="text-indigo-600">Framework</span> For
                        Trading Cryptocurrencies
                    </h1>

                    <div class="md:max-w-2xl mx-auto">
                        <p class="text-gray-800">
                            Jesse is not merely another bot. It is a framework
                            focusing on helping you develop your very own
                            trading strategies.
                        </p>
                    </div>

                    <div class="mb-6 mt-12">
                        <a
                            href="#"
                            class="py-2 px-4 md:py-4 md:px-6 mr-2 border select-none hover:bg-indigo-400 bg-indigo-500 rounded shadow-lg text-white"
                            >Why Jesse?</a
                        >

                        <a
                            href="#"
                            class="py-2 px-4 md:py-4 md:px-6 border select-none hover:bg-gray-100 bg-white rounded shadow-lg text-indigo-800"
                        >
                            Get Started <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, computed, watchEffect, onMounted, watch } from "vue";

const isOpen = ref(false);
</script>
