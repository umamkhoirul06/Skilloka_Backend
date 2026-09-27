"use client";

import React from "react";

export interface ShinyButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  children: React.ReactNode;
  onClick?: () => void;
  className?: string;
  variant?: "brand" | "dark" | "emerald" | "default";
}

export function ShinyButton({
  children,
  onClick,
  className = "",
  variant = "brand",
  ...props
}: ShinyButtonProps) {
  // Configured colors according to Skilloka design system
  const colorMap = {
    brand: {
      bg: "#2563eb",
      subtle: "#1d4ed8",
      highlight: "#93c5fd",
      shine: "#ffffff",
    },
    dark: {
      bg: "#0f172a",
      subtle: "#1e293b",
      highlight: "#38bdf8",
      shine: "#ffffff",
    },
    emerald: {
      bg: "#059669",
      subtle: "#047857",
      highlight: "#6ee7b7",
      shine: "#ffffff",
    },
    default: {
      bg: "#000000",
      subtle: "#1a1818",
      highlight: "#2563eb",
      shine: "#ffffff",
    },
  };

  const selected = colorMap[variant] || colorMap.brand;

  const styles = `
    @import url("https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,500;600;700&display=swap");

    @property --gradient-angle {
      syntax: "<angle>";
      initial-value: 0deg;
      inherits: false;
    }

    @property --gradient-angle-offset {
      syntax: "<angle>";
      initial-value: 0deg;
      inherits: false;
    }

    @property --gradient-percent {
      syntax: "<percentage>";
      initial-value: 5%;
      inherits: false;
    }

    @property --gradient-shine {
      syntax: "<color>";
      initial-value: white;
      inherits: false;
    }

    .shiny-cta {
      --shiny-cta-bg: ${selected.bg};
      --shiny-cta-bg-subtle: ${selected.subtle};
      --shiny-cta-fg: #ffffff;
      --shiny-cta-highlight: ${selected.highlight};
      --shiny-cta-highlight-subtle: ${selected.shine};
      --animation: gradient-angle linear infinite;
      --duration: 3s;
      --shadow-size: 2px;
      --transition: 800ms cubic-bezier(0.25, 1, 0.5, 1);
      
      isolation: isolate;
      position: relative;
      overflow: hidden;
      cursor: pointer;
      outline-offset: 4px;
      padding: 0.875rem 2rem;
      font-family: "Inter", sans-serif;
      font-size: 0.95rem;
      line-height: 1.2;
      font-weight: 600;
      border: 1px solid transparent;
      border-radius: 9999px;
      color: var(--shiny-cta-fg);
      background: linear-gradient(var(--shiny-cta-bg), var(--shiny-cta-bg)) padding-box,
        conic-gradient(
          from calc(var(--gradient-angle) - var(--gradient-angle-offset)),
          transparent,
          var(--shiny-cta-highlight) var(--gradient-percent),
          var(--gradient-shine) calc(var(--gradient-percent) * 2),
          var(--shiny-cta-highlight) calc(var(--gradient-percent) * 3),
          transparent calc(var(--gradient-percent) * 4)
        ) border-box;
      box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25), inset 0 0 0 1px var(--shiny-cta-bg-subtle);
      transition: var(--transition);
      transition-property: --gradient-angle-offset, --gradient-percent, --gradient-shine, box-shadow, transform;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .shiny-cta::before,
    .shiny-cta::after,
    .shiny-cta span::before {
      content: "";
      pointer-events: none;
      position: absolute;
      inset-inline-start: 50%;
      inset-block-start: 50%;
      translate: -50% -50%;
      z-index: -1;
    }

    .shiny-cta:active {
      translate: 0 1px;
    }

    /* Dots pattern */
    .shiny-cta::before {
      --size: calc(100% - var(--shadow-size) * 3);
      --position: 2px;
      --space: calc(var(--position) * 2);
      width: var(--size);
      height: var(--size);
      background: radial-gradient(
        circle at var(--position) var(--position),
        white calc(var(--position) / 4),
        transparent 0
      ) padding-box;
      background-size: var(--space) var(--space);
      background-repeat: space;
      mask-image: conic-gradient(
        from calc(var(--gradient-angle) + 45deg),
        black,
        transparent 10% 90%,
        black
      );
      border-radius: inherit;
      opacity: 0.35;
      z-index: -1;
    }

    /* Inner shimmer */
    .shiny-cta::after {
      --animation: shimmer linear infinite;
      width: 100%;
      aspect-ratio: 1;
      background: linear-gradient(
        -50deg,
        transparent,
        var(--shiny-cta-highlight),
        transparent
      );
      mask-image: radial-gradient(circle at bottom, transparent 40%, black);
      opacity: 0.6;
    }

    .shiny-cta span {
      z-index: 1;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .shiny-cta span::before {
      --size: calc(100% + 1rem);
      width: var(--size);
      height: var(--size);
      box-shadow: inset 0 -1ex 2rem 4px var(--shiny-cta-highlight);
      opacity: 0;
      transition: opacity var(--transition);
      animation: calc(var(--duration) * 1.5) breathe linear infinite;
    }

    /* Animate */
    .shiny-cta,
    .shiny-cta::before,
    .shiny-cta::after {
      animation: var(--animation) var(--duration),
        var(--animation) calc(var(--duration) / 0.4) reverse paused;
      animation-composition: add;
    }

    .shiny-cta:is(:hover, :focus-visible) {
      --gradient-percent: 20%;
      --gradient-angle-offset: 95deg;
      --gradient-shine: var(--shiny-cta-highlight-subtle);
      transform: translateY(-2px);
    }

    .shiny-cta:is(:hover, :focus-visible),
    .shiny-cta:is(:hover, :focus-visible)::before,
    .shiny-cta:is(:hover, :focus-visible)::after {
      animation-play-state: running;
    }

    .shiny-cta:is(:hover, :focus-visible) span::before {
      opacity: 1;
    }

    @keyframes gradient-angle {
      to {
        --gradient-angle: 360deg;
      }
    }

    @keyframes shimmer {
      to {
        rotate: 360deg;
      }
    }

    @keyframes breathe {
      from, to {
        scale: 1;
      }
      50% {
        scale: 1.2;
      }
    }
  `;

  return (
    <>
      <style dangerouslySetInnerHTML={{ __html: styles }} />
      <button
        className={`shiny-cta ${className}`}
        onClick={onClick}
        {...props}
      >
        <span>{children}</span>
      </button>
    </>
  );
}
