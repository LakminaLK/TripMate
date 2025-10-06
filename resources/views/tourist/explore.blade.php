<!DOCTYPE html>
<html lang="en">
<head>
    <title>Explore | TripMate</title>
    <x-tourist.head />
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col" x-data="scrollAnimations()" x-init="init()">

@php
    use Illuminate\Support\Facades\Auth;
    $tourist = Auth::guard('tourist')->user();
@endphp

<x-tourist.header />

@livewire('explore-activities')

<x-tourist.footer />

@livewireScripts

<script>
    function scrollAnimations() {
        return {
            init() {
                this.observeScrollElements();
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

    // Add entrance animations for activity cards
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to activity cards
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