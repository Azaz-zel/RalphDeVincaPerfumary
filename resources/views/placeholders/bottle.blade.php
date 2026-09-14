<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 500" width="400" height="500">
  <rect width="400" height="500" fill="#F5F3EF"/>
  <rect x="0.5" y="0.5" width="399" height="499" fill="none" stroke="#E7E1D6" stroke-width="1"/>
  <g fill="none" stroke="{{ $color }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M180 120h40v35h-40z"/>
    <path d="M170 155h60l14 20v185a10 10 0 0 1-10 10h-68a10 10 0 0 1-10-10V175z"/>
    <path d="M166 210h68"/>
  </g>
  @if($brand)
    <text x="200" y="388" font-family="Georgia, 'Times New Roman', serif" font-size="13" letter-spacing="2" fill="#A8A196" text-anchor="middle">{{ mb_strtoupper($brand) }}</text>
  @endif
  <text x="200" y="{{ $brand ? 418 : 400 }}" font-family="Georgia, 'Times New Roman', serif" font-size="{{ $fontSize }}" fill="#6B6459" text-anchor="middle">{{ $name }}</text>
</svg>
