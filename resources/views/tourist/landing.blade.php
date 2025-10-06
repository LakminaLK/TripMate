<!DOCTYPE html>
<html lang="en">
<head>
    <title>TripMate - Your Ultimate Travel Companion</title>
    <x-tourist.head />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Discover extraordinary travel experiences with TripMate. From pristine beaches to cultural adventures, we create unforgettable journeys tailored just for you.">
</head>
<body class="bg-white text-gray-800 font-sans antialiased" x-data="scrollAnimations()" x-init="init()">

<x-tourist.header :transparent="true" />

<!-- ✅ Hero Section - Enhanced Mobile Responsive -->
<section class="relative min-h-screen bg-cover bg-center flex items-center justify-center text-white overflow-hidden parallax-bg"
         style="background-image: url('/images/2.jpeg');">
    <!-- Enhanced Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/50 to-black/70"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-blue-900/30 to-transparent"></div>
    
    <!-- Hero Content -->
    <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto" x-data x-init="$el.classList.add('animate-fade-in')">
        <div class="space-y-6 lg:space-y-8">
            <!-- Main Headline -->
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold leading-tight animate-slide-up">
                Weaving Your Dreams into 
                <span class="block mt-2 gradient-text">
                    Unforgettable Adventures
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-lg sm:text-xl md:text-2xl max-w-4xl mx-auto leading-relaxed text-gray-100 animate-fade-in opacity-90">
                From pristine beachside resorts to unique cultural experiences. 
                Discover the top 1% of destinations handpicked by seasoned travelers.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-6 animate-pop">
                <a href="{{ route('tourist.explore') }}" 
                   class="group w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full font-semibold text-lg hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center">
                    <span>Start Your Journey</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="#discover" 
                   class="group w-full sm:w-auto px-8 py-4 border-2 border-white/30 backdrop-blur-sm text-white rounded-full font-semibold text-lg hover:bg-white/10 transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-play mr-2"></i>
                    <span>Watch Our Story</span>
                </a>
            </div>
            
            <!-- Trust Indicators -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-8 max-w-2xl mx-auto animate-fade-in">
                <div class="text-center trust-indicator">
                    <div class="text-2xl sm:text-3xl font-bold text-blue-400 mb-1">500+</div>
                    <div class="text-xs sm:text-sm text-gray-300">Destinations</div>
                </div>
                <div class="text-center trust-indicator">
                    <div class="text-2xl sm:text-3xl font-bold text-purple-400 mb-1">10K+</div>
                    <div class="text-xs sm:text-sm text-gray-300">Happy Travelers</div>
                </div>
                <div class="text-center trust-indicator">
                    <div class="text-2xl sm:text-3xl font-bold text-green-400 mb-1">4.9★</div>
                    <div class="text-xs sm:text-sm text-gray-300">Rating</div>
                </div>
                <div class="text-center trust-indicator">
                    <div class="text-2xl sm:text-3xl font-bold text-yellow-400 mb-1">24/7</div>
                    <div class="text-xs sm:text-sm text-gray-300">Support</div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce hidden sm:block">
            <div class="w-6 h-10 border-2 border-white/50 rounded-full flex justify-center cursor-pointer">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </div>
</section>

<!-- ✅ Welcome Section - Professional & Mobile Responsive -->
<section id="discover" class="py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-blue-50/30 scroll-animate" data-delay="100">
    <div class="max-w-7xl mx-auto">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16 scroll-animate" data-delay="200">
            <div class="inline-block mb-4">
                <span class="px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold tracking-wide uppercase">
                    About TripMate
                </span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                Your Gateway to 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 pb-3 pt-1">
                    Extraordinary Experiences
                </span>
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mx-auto mb-8"></div>
        </div>
        
        <!-- Content Grid -->
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- Text Content -->
            <div class="space-y-6 scroll-animate" data-delay="300">
                <p class="text-lg sm:text-xl text-gray-700 leading-relaxed font-medium">
                    Like you, we are travelers. Exploration runs in our blood. It's who we are, and why we do what we do.
                </p>
                <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                    We are passionate, curious and deeply committed to sustainably exploring our incredible world. 
                    As part of a global community, we're excited to embrace and discover our planet, uncovering the 
                    rich cultures, histories, wildlife and natural beauty that make our travels so special.
                </p>
                <p class="text-base sm:text-lg text-gray-600 leading-relaxed">
                    At TripMate, we create transformative travel experiences that fulfill that deep-seated urge for 
                    connecting and learning. So, ask yourself this – where will your passion for travel take you?
                </p>
                
                <!-- Feature Highlights -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 scroll-animate" data-delay="400">
                    <div class="flex items-center space-x-3 transform hover:scale-105 transition-transform duration-300">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-shield-alt text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Secure Booking</div>
                            <div class="text-sm text-gray-600">Protected payments</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 transform hover:scale-105 transition-transform duration-300">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-headset text-green-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">24/7 Support</div>
                            <div class="text-sm text-gray-600">Always here to help</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Visual Elements -->
            <div class="relative order-first lg:order-last scroll-animate" data-delay="500">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl transform hover:scale-105 transition-transform duration-500">
                    <img src="/images/2.jpeg" alt="Travel Experience" class="w-full h-80 sm:h-96 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h3 class="text-xl font-bold mb-2">Start Your Adventure</h3>
                        <p class="text-sm opacity-90">Discover the world with confidence</p>
                    </div>
                </div>
                
                <!-- Floating Verification Card -->
                <div class="absolute -bottom-6 -right-6 bg-white rounded-xl shadow-xl p-6 hidden sm:block scroll-animate" data-delay="600">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                            ✓
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">Verified Quality</div>
                            <div class="text-sm text-gray-600">Trusted by thousands</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ✅ Popular Activities - Enhanced Mobile Design -->
<section class="py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8 bg-white" x-data="{ 
    shownActivities: [],
    init() {
        this.observeActivities();
    },
    observeActivities() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.shownActivities.push(entry.target.dataset.id);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.activity-card').forEach(card => {
            observer.observe(card);
        });
    }
}">
    <div class="max-w-7xl mx-auto">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16 scroll-animate" data-delay="100">
            <div class="inline-block mb-4">
                <span class="px-4 py-2 bg-purple-100 text-purple-600 rounded-full text-sm font-semibold tracking-wide uppercase">
                    Featured Activities
                </span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                Discover Amazing 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 pb-3 pt-1">
                    Experiences
                </span>
            </h2>
            <p class="text-base sm:text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Explore our handpicked selection of unforgettable activities and create memories that last a lifetime.
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mx-auto mt-6"></div>
        </div>

        @php $list = ($homeActivities ?? collect())->take(6); @endphp
        @if($list->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($list as $index => $a)
                    @php
                        $raw = $a->image;
                        $path = $raw ? (strpos($raw, 'public/') === 0 ? substr($raw, 7) : $raw) : null;
                        $img = $path
                            ? (preg_match('#^https?://#', $path) || strpos($path, '/') === 0
                                ? $path
                                : asset('storage/'.ltrim($path, '/')))
                            : asset('images/placeholder.jpg');
                    @endphp
                    
                    <a href="{{ route('tourist.activity.show', ['activity' => $a->id]) }}" 
                       class="activity-card group relative block rounded-2xl overflow-hidden shadow-lg transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-2"
                       data-id="{{ $a->id }}"
                       :class="{ 'opacity-0 translate-y-8': !shownActivities.includes('{{ $a->id }}'), 'opacity-100 translate-y-0': shownActivities.includes('{{ $a->id }}') }"
                       style="transition-delay: {{ $index * 100 }}ms">
                        
                        <!-- Image Container -->
                        <div class="relative h-64 sm:h-72 lg:h-80 overflow-hidden">
                            <img src="{{ $img }}" 
                                 alt="{{ $a->name }}"
                                 class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                            
                            <!-- Overlay with gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-70 transition-opacity group-hover:opacity-90"></div>

                            <!-- Price Badge -->
                            @if(!is_null($a->price))
                                <div class="absolute top-4 right-4 px-3 py-2 bg-white/95 backdrop-blur-sm rounded-full shadow-lg">
                                    <span class="text-blue-600 font-bold text-sm sm:text-base">${{ number_format($a->price, 2) }}</span>
                                </div>
                            @endif

                            <!-- Content overlay -->
                            <div class="absolute inset-0 p-4 sm:p-6 flex flex-col justify-end text-white">
                                <h3 class="text-lg sm:text-xl font-bold mb-2 transform transition-transform group-hover:-translate-y-2">
                                    {{ $a->name }}
                                </h3>
                                
                                <!-- Features -->
                                <div class="flex items-center space-x-4 mb-4 text-xs sm:text-sm opacity-90">
                                    <span class="flex items-center">
                                        <i class="fas fa-star text-yellow-400 mr-1"></i>
                                        4.8
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        2-3 hours
                                    </span>
                                </div>
                                
                                <!-- Animated arrow -->
                                <div class="inline-flex items-center text-blue-400 transform translate-y-8 opacity-0 transition-all group-hover:translate-y-0 group-hover:opacity-100">
                                    <span class="font-medium mr-2 text-sm sm:text-base">Explore Now</span>
                                    <i class="fas fa-arrow-right transform transition-transform group-hover:translate-x-2"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- View All Button -->
            <div class="text-center mt-12 lg:mt-16">
                <a href="{{ route('tourist.explore') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full 
                          font-semibold text-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 group">
                    <span>Explore All Activities</span>
                    <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        @else
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl p-12 text-center shadow-lg">
                <div class="w-24 h-24 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-hiking text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Amazing Adventures Await</h3>
                <p class="text-gray-600 max-w-md mx-auto">We're curating incredible experiences just for you. Check back soon for exciting new activities!</p>
            </div>
        @endif
    </div>
</section>

<!-- ✅ FAQs - Enhanced Mobile Design -->
<section class="py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-blue-50 scroll-animate" data-delay="100">
    <div class="max-w-4xl mx-auto">
        <!-- Section Header -->
        <div class="text-center mb-12 lg:mb-16 scroll-animate" data-delay="200">
            <div class="inline-block mb-4">
                <span class="px-4 py-2 bg-green-100 text-green-600 rounded-full text-sm font-semibold tracking-wide uppercase">
                    Support
                </span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                Frequently Asked 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 pb-3 pt-1">
                    Questions
                </span>
            </h2>
            <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Got questions? We've got answers. Find everything you need to know about our services.
            </p>
            <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full mx-auto mt-6"></div>
        </div>
        
        <!-- FAQ Items -->
        <div class="space-y-4 sm:space-y-6">
            @foreach ([
                [
                    'question' => 'How do I create a booking?',
                    'answer' => 'Creating a booking is simple! Browse our activities, select your preferred experience, choose your dates, and complete the secure checkout process. You\'ll receive instant confirmation via email.'
                ],
                [
                    'question' => 'What is the cancellation Policy?',
                    'answer' => 'We offer flexible cancellation policies. Most bookings can be cancelled up to 24-48 hours before the activity date for a full refund. Specific terms vary by activity and will be clearly displayed during booking.'
                ],
                [
                    'question' => 'How can I find out more about Sri Lankan destinations?',
                    'answer' => 'Our destination guides provide comprehensive information about each location, including local culture, best times to visit, and insider tips. You can also contact our travel experts for personalized recommendations.'
                ],
                [
                    'question' => 'How do I contact you for support?',
                    'answer' => 'We\'re here to help! You can reach our 24/7 customer support team via live chat, email, or phone. Our emergency hotline is always available for urgent assistance during your travels.'
                ]
            ] as $index => $faq)
                <div x-data="{ open: false }" 
                     class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-xl scroll-animate" 
                     data-delay="{{ 300 + ($index * 100) }}">
                    <button @click="open = !open" 
                            class="w-full px-6 sm:px-8 py-6 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <span class="text-lg sm:text-xl font-semibold text-gray-900 pr-4">{{ $faq['question'] }}</span>
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white transform transition-transform duration-300"
                                 :class="{ 'rotate-180': open }">
                                <i class="fas fa-chevron-down text-sm"></i>
                            </div>
                        </div>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-96"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 max-h-96"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="px-6 sm:px-8 pb-6 overflow-hidden">
                        <div class="pt-2 border-t border-gray-100">
                            <p class="text-base sm:text-lg text-gray-600 leading-relaxed">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Contact CTA -->
        <div class="text-center mt-12 scroll-animate" data-delay="700">
            <p class="text-gray-600 mb-6">Still have questions?</p>
            <a href="#contact" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-full font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                <i class="fas fa-comments mr-2"></i>
                <span>Contact Support</span>
            </a>
        </div>
    </div>
</section>

<x-tourist.footer />

<!-- ✅ Enhanced Mobile-First Animations & Styles -->
<style>
    /* Base Animations */
    .animate-fade-in { 
        animation: fadeIn 1.2s ease-out forwards; 
        opacity: 0; 
    }
    
    .animate-slide-up { 
        animation: slideUp 1.2s ease-out forwards; 
        opacity: 0; 
    }
    
    .animate-pop { 
        animation: pop 0.6s ease-out forwards; 
        opacity: 0;
        transform: scale(0.9);
    }

    /* Scroll Animations */
    .scroll-animate {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-animate.animate {
        opacity: 1;
        transform: translateY(0);
    }

    .scroll-animate-left {
        opacity: 0;
        transform: translateX(-50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-animate-left.animate {
        opacity: 1;
        transform: translateX(0);
    }

    .scroll-animate-right {
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-animate-right.animate {
        opacity: 1;
        transform: translateX(0);
    }

    .scroll-animate-scale {
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .scroll-animate-scale.animate {
        opacity: 1;
        transform: scale(1);
    }

    /* Keyframe Definitions */
    @keyframes fadeIn { 
        to { opacity: 1; } 
    }
    
    @keyframes slideUp { 
        from { 
            opacity: 0; 
            transform: translateY(30px); 
        } 
        to { 
            opacity: 1; 
            transform: translateY(0); 
        } 
    }
    
    @keyframes pop { 
        0% { 
            transform: scale(0.9); 
            opacity: 0; 
        } 
        50% { 
            transform: scale(1.02); 
            opacity: 0.8; 
        } 
        100% { 
            transform: scale(1); 
            opacity: 1; 
        } 
    }

    /* Enhanced Gradient Text Fix */
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
        padding-bottom: 4px;
        padding-top: 2px;
        display: inline-block;
        line-height: 1.2;
    }

    /* Additional gradient text support for better rendering */
    .text-transparent {
        color: transparent;
    }

    /* Ensure proper spacing for gradient text elements */
    h1 .bg-clip-text,
    h2 .bg-clip-text {
        margin-bottom: 0.25rem;
        overflow: visible;
    }

    /* Specific fix for gradient text clipping */
    .gradient-text {
        background: linear-gradient(90deg, #60a5fa, #a855f7, #ec4899);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
        display: inline-block;
        padding: 4px 0 8px 0;
        line-height: 1.1;
        box-decoration-break: clone;
        -webkit-box-decoration-break: clone;
    }

    /* Mobile-specific optimizations */
    @media (max-width: 640px) {
        .activity-card {
            margin-bottom: 1rem;
        }
        
        .hero-scroll-indicator {
            bottom: 2rem;
        }

        .scroll-animate {
            transform: translateY(30px);
        }
    }

    /* Smooth scrolling for anchor links */
    html {
        scroll-behavior: smooth;
    }

    /* Custom scrollbar for webkit browsers */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #2563eb, #7c3aed);
    }

    /* Performance optimizations */
    .activity-card {
        will-change: transform;
    }
    
    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }

    /* Parallax effect for hero section */
    .parallax-bg {
        transform: translateZ(0);
        will-change: transform;
    }
</style>

<!-- ✅ Enhanced Scroll Animation JavaScript -->
<script>
    function scrollAnimations() {
        return {
            init() {
                this.observeScrollElements();
                this.addParallaxEffect();
                this.addSmoothScrollToLinks();
            },

            observeScrollElements() {
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const delay = entry.target.dataset.delay || 0;
                            setTimeout(() => {
                                entry.target.classList.add('animate');
                            }, delay);
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                // Observe all scroll-animate elements
                document.querySelectorAll('.scroll-animate, .scroll-animate-left, .scroll-animate-right, .scroll-animate-scale').forEach(el => {
                    observer.observe(el);
                });
            },

            addParallaxEffect() {
                let ticking = false;
                
                const updateParallax = () => {
                    const scrolled = window.pageYOffset;
                    const parallaxElements = document.querySelectorAll('.parallax-bg');
                    
                    parallaxElements.forEach(element => {
                        const speed = 0.5;
                        const yPos = -(scrolled * speed);
                        element.style.transform = `translate3d(0, ${yPos}px, 0)`;
                    });
                    
                    ticking = false;
                };

                const requestTick = () => {
                    if (!ticking) {
                        requestAnimationFrame(updateParallax);
                        ticking = true;
                    }
                };

                window.addEventListener('scroll', requestTick);
            },

            addSmoothScrollToLinks() {
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            const offsetTop = target.offsetTop - 80;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                        }
                    });
                });
            }
        }
    }

    // Additional entrance animations for specific elements
    document.addEventListener('DOMContentLoaded', function() {
        // Stagger animation for trust indicators
        const trustIndicators = document.querySelectorAll('.trust-indicator');
        trustIndicators.forEach((indicator, index) => {
            indicator.style.animationDelay = `${index * 0.2}s`;
        });

        // Add hover effects to cards
        const cards = document.querySelectorAll('.activity-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>

</body>
</html>