import React from "react";
import { ShinyButton } from "@/components/ui/shiny-button";
import { ArrowRight, Sparkles, Award } from "lucide-react";

export default function DemoOne() {
  return (
    <div className="min-h-screen flex flex-col items-center justify-center bg-slate-950 p-6 gap-6 text-white font-sans">
      <div className="text-center space-y-2">
        <h1 className="text-2xl font-bold tracking-tight">Skilloka UI Showcase</h1>
        <p className="text-xs text-slate-400">
          Implementasi Shiny Button dengan Brand Tokens Skilloka (PRD Baseline)
        </p>
      </div>

      <div className="flex flex-wrap items-center justify-center gap-4">
        {/* Brand Blue Variant (Skilloka Primary) */}
        <ShinyButton variant="brand" onClick={() => alert("Daftar Kursus!")}>
          <span>Daftar Pelatihan</span>
          <ArrowRight className="w-4 h-4 ml-1" />
        </ShinyButton>

        {/* Emerald Variant (Sertifikat / Konsultasi) */}
        <ShinyButton variant="emerald" onClick={() => alert("Terbitkan Sertifikat!")}>
          <Award className="w-4 h-4 mr-1" />
          <span>Terbitkan Sertifikat</span>
        </ShinyButton>

        {/* Dark Slate Variant (Super Admin Portal) */}
        <ShinyButton variant="dark" onClick={() => alert("Super Admin Portal!")}>
          <Sparkles className="w-4 h-4 mr-1 text-cyan-400" />
          <span>Super Admin Access</span>
        </ShinyButton>
      </div>
    </div>
  );
}
