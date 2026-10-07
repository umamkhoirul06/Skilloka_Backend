import React from 'react';

interface ChatBotAvatar3DProps {
  size?: number;
  onClick?: () => void;
  className?: string;
  useImage?: boolean;
}

export default function ChatBotAvatar3D({ 
  size = 120, 
  onClick,
  className = '',
  useImage = true
}: ChatBotAvatar3DProps) {
  if (useImage) {
    return (
      <div 
        onClick={onClick}
        className={`relative flex items-center justify-center cursor-pointer select-none group transition-transform duration-300 hover:scale-105 active:scale-95 ${className}`}
        style={{ width: size, height: size }}
      >
        {/* Soft Dynamic Floating Shadow */}
        <div 
          className="absolute -bottom-1 w-3/5 h-2.5 bg-slate-900/30 rounded-full blur-sm transition-all duration-700 group-hover:w-4/5 group-hover:opacity-60" 
          style={{ animation: 'botShadowPulse 3s ease-in-out infinite' }}
        />

        {/* Floating 3D Bot Character */}
        <div 
          className="relative w-full h-full flex items-center justify-center"
          style={{ animation: 'botFloat 3.5s ease-in-out infinite' }}
        >
          {/* Golden Aura Glow */}
          <div 
            className="absolute inset-2 rounded-full bg-amber-400/20 blur-xl opacity-70 group-hover:opacity-100 transition-opacity pointer-events-none"
          />

          <img 
            src="/assets/images/chatbot-3d-transparent.png" 
            alt="Loka - 3D AI Assistant Skilloka" 
            className="w-full h-full object-contain drop-shadow-2xl z-10 transition-transform duration-300 group-hover:rotate-2"
          />
        </div>
      </div>
    );
  }

  // Pure Vector CSS 3D Fallback Mode
  return (
    <div 
      onClick={onClick}
      className={`relative flex items-center justify-center cursor-pointer select-none group ${className}`}
      style={{ width: size, height: size }}
    >
      {/* Efek Bayangan Melayang di Bawah */}
      <div className="absolute bottom-2 w-2/3 h-3 bg-slate-900/20 rounded-full blur-md animate-[pulse_3s_ease-in-out_infinite]" />

      {/* Kontainer Utama 3D dengan Animasi Melayang */}
      <div className="relative flex items-center justify-center w-full h-full animate-[bounce_4s_ease-in-out_infinite]">
        
        {/* Cincin Orbit Emas 3D Bagian Belakang */}
        <div 
          className="absolute border-[3px] border-[#F59E0B] rounded-full opacity-80"
          style={{
            width: `${size * 0.9}px`,
            height: `${size * 0.35}px`,
            transform: 'rotate(-25deg) rotateX(70deg)',
            boxShadow: '0 0 12px rgba(245, 158, 11, 0.6)'
          }}
        />

        {/* Bodi Utama Bot (Bola Metalik Navy) */}
        <div 
          className="relative flex flex-col items-center justify-center rounded-full z-10 shadow-2xl transition-transform duration-300 group-hover:scale-105"
          style={{
            width: `${size * 0.65}px`,
            height: `${size * 0.65}px`,
            background: 'radial-gradient(circle at 35% 30%, #1E293B, #0F172A 70%, #020617 100%)',
            boxShadow: 'inset -4px -4px 10px rgba(0,0,0,0.6), inset 4px 4px 10px rgba(255,255,255,0.2), 0 10px 25px rgba(15,23,42,0.4)'
          }}
        >
          {/* Topi Toga Mini di Atas Bot */}
          <div className="absolute -top-3 flex flex-col items-center">
            {/* Papan Toga Segiempat Bersudut */}
            <div 
              className="bg-[#0A0F1D] border border-slate-700/60 shadow-md"
              style={{
                width: `${size * 0.45}px`,
                height: `${size * 0.45}px`,
                transform: 'rotateX(60deg) rotateZ(45deg)'
              }}
            />
            {/* Tali Toga Emas */}
            <div className="w-[2px] h-3 bg-[#F59E0B] -mt-1 self-end mr-2 shadow-sm" />
          </div>

          {/* Visor Layar Digital */}
          <div 
            className="flex items-center justify-center gap-2 bg-[#050811] rounded-full px-3 py-1.5 border border-slate-700/50"
            style={{ width: `${size * 0.42}px`, height: `${size * 0.22}px` }}
          >
            {/* Mata Kiri Digital */}
            <div className="w-2.5 h-2.5 rounded-full bg-[#38BDF8] shadow-[0_0_8px_#38BDF8] animate-[ping_5s_cubic-bezier(0,0,0.2,1)_infinite]" />
            {/* Mata Kanan Digital */}
            <div className="w-2.5 h-2.5 rounded-full bg-[#38BDF8] shadow-[0_0_8px_#38BDF8] animate-[ping_5s_cubic-bezier(0,0,0.2,1)_infinite]" />
          </div>
        </div>

        {/* Cincin Orbit Emas 3D Bagian Depan */}
        <div 
          className="absolute border-t-[3px] border-r-[3px] border-[#F59E0B] rounded-full z-20 pointer-events-none"
          style={{
            width: `${size * 0.9}px`,
            height: `${size * 0.35}px`,
            transform: 'rotate(-25deg) rotateX(70deg)',
            filter: 'drop-shadow(0 0 6px #F59E0B)'
          }}
        />

      </div>
    </div>
  );
}
