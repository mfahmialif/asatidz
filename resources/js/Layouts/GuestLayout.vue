<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { usePublicTheme } from '../Pages/public/usePublicTheme';

const { resolvedTheme, initTheme } = usePublicTheme();
const canvasRef = ref(null);
let animationFrameId = null;

onMounted(() => {
    initTheme();

    const canvas = canvasRef.value;
    const ctx = canvas.getContext('2d');
    let width, height;
    
    // Config
    const particles = [];
    const particleCount = 45;
    
    const resize = () => {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width;
        canvas.height = height;
    };
    
    window.addEventListener('resize', resize);
    resize();
    
    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.size = Math.random() * 2 + 0.5;
            this.speedX = Math.random() * 0.4 - 0.2;
            this.speedY = Math.random() * 0.4 - 0.2;
            this.baseOpacity = Math.random() * 0.5 + 0.1;
        }
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            
            if (this.x < 0) this.x = width;
            if (this.x > width) this.x = 0;
            if (this.y < 0) this.y = height;
            if (this.y > height) this.y = 0;
        }
        draw() {
            // Adaptive particle color based on theme
            const color = resolvedTheme.value === 'dark' ? '255, 255, 255' : '59, 130, 246';
            ctx.fillStyle = `rgba(${color}, ${this.baseOpacity})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }
    
    for (let i = 0; i < particleCount; i++) {
        particles.push(new Particle());
    }
    
    const animate = () => {
        ctx.clearRect(0, 0, width, height);
        
        // Draw lines between close particles
        for (let i = 0; i < particles.length; i++) {
            for (let j = i; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 100) {
                    const color = resolvedTheme.value === 'dark' ? '255,255,255' : '59,130,246';
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(${color}, ${0.15 - distance/1000})`;
                    ctx.lineWidth = 0.5;
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }

        particles.forEach(p => {
            p.update();
            p.draw();
        });
        animationFrameId = requestAnimationFrame(animate);
    };
    
    animate();
});

onUnmounted(() => {
    if (animationFrameId) cancelAnimationFrame(animationFrameId);
    window.removeEventListener('resize', () => {});
});
</script>

<template>
    <div :class="['auth-wrapper min-h-screen relative flex flex-col items-center justify-center p-4 transition-colors duration-500 overflow-hidden', resolvedTheme === 'dark' ? 'dark bg-[#070b16]' : 'bg-[#eef4fb]']">
        <!-- Particle Canvas -->
        <canvas ref="canvasRef" class="absolute inset-0 z-0 pointer-events-none opacity-80"></canvas>
        
        <!-- Elegant decorative blobs -->
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-sky-500/10 dark:bg-sky-500/5 rounded-full blur-[80px] pointer-events-none mix-blend-multiply dark:mix-blend-screen"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/10 dark:bg-blue-600/5 rounded-full blur-[80px] pointer-events-none mix-blend-multiply dark:mix-blend-screen"></div>
        
        <div class="z-10 w-full max-w-[420px]">
            <!-- Logo -->
            <div class="flex justify-center mb-10">
                <Link href="/" class="flex items-center gap-3 group">
                    <div class="w-14 h-14 flex items-center justify-center bg-white dark:bg-white/10 rounded-2xl shadow-xl shadow-sky-500/10 dark:shadow-sky-500/5 border border-white/50 dark:border-white/10 transition-all duration-300 group-hover:scale-105 group-hover:shadow-2xl">
                        <span class="material-symbols-outlined text-sky-600 dark:text-sky-400 text-3xl">school</span>
                    </div>
                    <span class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Asatidz</span>
                </Link>
            </div>
            
            <!-- Card -->
            <div class="auth-card relative bg-white/90 dark:bg-[#0f172a]/80 backdrop-blur-2xl border border-white/40 dark:border-white/10 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] dark:shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] rounded-[1.5rem] p-8 sm:p-10 transition-all duration-300">
                <slot />
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    &copy; {{ new Date().getFullYear() }} Asatidz. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</template>

<style>
/* Global Auth Styles */
.auth-card h2 {
    font-size: 1.65rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin-bottom: 0.5rem;
}
.dark .auth-card h2 {
    color: #ffffff;
}

.auth-card p.subtitle {
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 2rem;
    line-height: 1.5;
}
.dark .auth-card p.subtitle {
    color: #94a3b8;
}

.auth-card label {
    font-size: 0.85rem !important;
    font-weight: 700 !important;
    color: #334155 !important;
    margin-bottom: 0.4rem !important;
    display: block !important;
}
.dark .auth-card label {
    color: #cbd5e1 !important;
}

.auth-card input[type="text"],
.auth-card input[type="email"],
.auth-card input[type="password"] {
    width: 100% !important;
    height: 3.25rem !important;
    border-radius: 0.85rem !important;
    border: 1px solid #cbd5e1 !important;
    background: #ffffff !important;
    padding: 0 1rem !important;
    font-size: 0.95rem !important;
    color: #0f172a !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02) inset !important;
}

.dark .auth-card input[type="text"],
.dark .auth-card input[type="email"],
.dark .auth-card input[type="password"] {
    border-color: rgba(255,255,255,0.1) !important;
    background: rgba(255,255,255,0.03) !important;
    color: #f8fafc !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) inset !important;
}

.auth-card input[type="text"]:focus,
.auth-card input[type="email"]:focus,
.auth-card input[type="password"]:focus {
    border-color: #0ea5e9 !important;
    box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15) !important;
    outline: none !important;
}
.dark .auth-card input[type="text"]:focus,
.dark .auth-card input[type="email"]:focus,
.dark .auth-card input[type="password"]:focus {
    border-color: #38bdf8 !important;
    box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15) !important;
}

/* Base button override (Inertia forms) */
.auth-card button[type="submit"],
.auth-card .btn-primary {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    height: 3.25rem !important;
    border-radius: 0.85rem !important;
    background: linear-gradient(135deg, #0ea5e9, #0284c7) !important;
    color: white !important;
    font-weight: 800 !important;
    font-size: 0.95rem !important;
    padding: 0 1.5rem !important;
    border: none !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 10px 20px -8px rgba(14, 165, 233, 0.5) !important;
    text-transform: none !important;
    letter-spacing: normal !important;
    margin-top: 1rem !important;
}
.auth-card button[type="submit"]:hover,
.auth-card .btn-primary:hover {
    background: linear-gradient(135deg, #0284c7, #0369a1) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 15px 25px -8px rgba(14, 165, 233, 0.6) !important;
}
.dark .auth-card button[type="submit"],
.dark .auth-card .btn-primary {
    background: linear-gradient(135deg, #38bdf8, #0ea5e9) !important;
    color: #0f172a !important;
    box-shadow: 0 10px 20px -8px rgba(56, 189, 248, 0.4) !important;
}
.dark .auth-card button[type="submit"]:hover,
.dark .auth-card .btn-primary:hover {
    background: linear-gradient(135deg, #7dd3fc, #38bdf8) !important;
    box-shadow: 0 15px 25px -8px rgba(56, 189, 248, 0.5) !important;
}

.auth-card button[disabled] {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    transform: none !important;
}

.auth-card a {
    color: #0ea5e9 !important;
    font-weight: 700 !important;
    text-decoration: none !important;
    transition: color 0.2s ease !important;
}
.auth-card a:hover {
    color: #0284c7 !important;
}
.dark .auth-card a {
    color: #38bdf8 !important;
}
.dark .auth-card a:hover {
    color: #7dd3fc !important;
}

.auth-card input[type="checkbox"] {
    border-radius: 0.35rem !important;
    border: 1px solid #cbd5e1 !important;
    color: #0ea5e9 !important;
    width: 1.15rem !important;
    height: 1.15rem !important;
    cursor: pointer;
}
.dark .auth-card input[type="checkbox"] {
    border-color: rgba(255,255,255,0.2) !important;
    background: rgba(255,255,255,0.05) !important;
}
.auth-card input[type="checkbox"]:focus {
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15) !important;
}
</style>
