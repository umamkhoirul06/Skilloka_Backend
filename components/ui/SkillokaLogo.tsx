import React from 'react';

interface SkillokaLogoProps {
  variant?: 'full' | 'icon-only'; // 'full' (ikon + teks) atau 'icon-only' (hanya simbol)
  className?: string;
  textColor?: string;
  isDark?: boolean;
}

export default function SkillokaLogo({ 
  variant = 'full', 
  className = 'h-9',
  textColor = 'text-slate-900 dark:text-white',
  isDark = false
}: SkillokaLogoProps) {
  const primaryStroke = isDark ? '#FFFFFF' : '#0F172A';
  const primaryFill = isDark ? '#FFFFFF' : '#0F172A';

  return (
    <div className={`flex items-center gap-3 select-none ${className}`}>
      {/* Simbol Vektor Flat S-Mark */}
      <svg
        viewBox="0 0 100 100"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        className="h-full w-auto aspect-square flex-shrink-0"
      >
        {/* Sudut Toga & Garis Atas Huruf S (Deep Navy / White on Dark) */}
        <path
          d="M50 10L82 25L50 40L18 25L50 10Z"
          fill={primaryFill}
        />
        {/* Badan Utama Huruf S */}
        <path
          d="M32 36C24 42 22 52 26 62C30 72 40 78 50 82C62 86 74 84 80 76C84 70 82 62 76 56C70 50 58 46 48 42L36 38"
          stroke={primaryStroke}
          strokeWidth="14"
          strokeLinecap="round"
          strokeLinejoin="round"
        />
        {/* Aksen Panah Upskilling / Pintu Peluang (Vibrant Gold) */}
        <path
          d="M38 78C48 74 60 64 68 50L78 40M78 40H62M78 40V56"
          stroke="#F59E0B"
          strokeWidth="10"
          strokeLinecap="round"
          strokeLinejoin="round"
        />
      </svg>

      {/* Wordmark Tipografi Skilloka */}
      {variant === 'full' && (
        <span className={`text-2xl font-black tracking-tight font-sans ${textColor}`}>
          Skill<span className="text-[#F59E0B]">oka</span>
        </span>
      )}
    </div>
  );
}
