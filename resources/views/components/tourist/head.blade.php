<!-- Meta tags and base styles for tourist pages -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@livewireStyles
<style>
    [x-cloak] { display: none !important; }
    
    /* Professional animations */
    .fade-in { animation: fadeIn 0.8s ease-out forwards; opacity: 0; }
    .slide-up { animation: slideUp 0.8s ease-out forwards; opacity: 0; transform: translateY(30px); }
    .slide-in-left { animation: slideInLeft 0.8s ease-out forwards; opacity: 0; transform: translateX(-50px); }
    .scale-in { animation: scaleIn 0.6s ease-out forwards; opacity: 0; transform: scale(0.9); }
    .float { animation: float 3s ease-in-out infinite; }
    
    /* Smooth transitions */
    .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .hover-lift:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .hover-scale:hover { transform: scale(1.02); }
    
    /* Professional gradients */
    .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .glass { backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.1); }
    
    @keyframes fadeIn { to { opacity: 1; } }
    @keyframes slideUp { to { opacity: 1; transform: translateY(0); } }
    @keyframes slideInLeft { to { opacity: 1; transform: translateX(0); } }
    @keyframes scaleIn { to { opacity: 1; transform: scale(1); } }
    @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #667eea; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #764ba2; }
    
    /* Custom blue scrollbar */
    ::-webkit-scrollbar { 
        width: 8px; 
    }
    ::-webkit-scrollbar-track { 
        background: #f1f1f1; 
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb { 
        background: linear-gradient(45deg, #667eea, #764ba2); 
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    ::-webkit-scrollbar-thumb:hover { 
        background: linear-gradient(45deg, #5a67d8, #6b46c1);
        transform: scale(1.1);
    }
    
    .blue-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #667eea #f1f1f1;
    }
    
    /* Enhanced image hover effects */
    .image-hover-container {
        position: relative;
        overflow: hidden;
    }
    
    .image-hover-container img {
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .image-hover-container:hover img {
        transform: scale(1.1);
    }
    
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Custom shadow */
    .custom-shadow { box-shadow: 0 0 50px -12px rgb(0 0 0 / 0.25); }
    
    /* Gallery overlay */
    .gallery-overlay {
        background: linear-gradient(45deg, rgba(0,0,0,0.7), rgba(0,0,0,0.3));
    }
</style>
<link rel="icon" href="{{ asset('/images/tm1.png') }}" type="image/x-icon">