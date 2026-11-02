@extends('layouts.storefront')

@section('meta_title', ($cmsHome?->meta_title ?: config('app.name', 'Nai Printers')) . ' | ' . config('app.tagline'))
@section('meta_description', $cmsHome?->meta_description ?: config('app.tagline'))

@push('head')
<style>
    :root {
        --card: #ffffff;
        --line: #d9d9d9;
        --red: #b10f1e;
    }

    .home-wrap {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .home-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-radius: 10px;
        overflow: hidden;
    }

    .pet-promo-slide {
        position: relative;
        min-height: 376px;
        overflow: hidden;
        background: transparent;
        color: #00304a;
    }

    .pet-promo-background {
        position: absolute;
        inset: 0;
        z-index: 0;
        background: #f7a9b7;
    }

    .pet-promo-dog {
        position: absolute;
        left: 0;
        bottom: 0;
        z-index: 1;
        width: min(38vw, 640px);
        height: 100%;
        object-fit: cover;
        object-position: center bottom;
        transform: translate3d(var(--dog-shift-x, 0), var(--dog-shift-y, 0), 0) scale(var(--dog-scale, 1));
        transition: transform 160ms ease-out;
        will-change: transform;
    }

    .pet-promo-content {
        position: relative;
        z-index: 3;
        width: min(42vw, 650px);
        margin: 0 auto;
        padding: 74px 16px 52px;
        text-align: center;
    }

    .pet-promo-icons {
        font-size: 29px;
        line-height: 1;
        letter-spacing: 0;
        font-weight: 900;
    }

    .pet-promo-title {
        margin-top: 32px;
        font-family: 'Fredoka One', cursive !important;
        font-size: clamp(2.45rem, 4vw, 4.25rem);
        line-height: 0.95;
        font-weight: 400;
        letter-spacing: 0;
    }

    .pet-promo-subtitle {
        margin-top: 18px;
        font-family: 'Fredoka One', cursive !important;
        font-size: clamp(1.55rem, 2.1vw, 2.55rem);
        line-height: 1;
        font-weight: 400;
        letter-spacing: 0.02em;
    }

    .pet-promo-copy {
        margin: 28px auto 0;
        max-width: 420px;
        color: currentColor;
        font-family: 'Fredoka One', cursive !important;
        font-size: clamp(1rem, 1.35vw, 1.45rem);
        line-height: 1.28;
        font-weight: 400;
    }

    .pet-promo-title-accent {
        color: var(--pet-promo-accent, var(--red));
    }

    .pet-promo-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 150px;
        height: 46px;
        margin-top: 30px;
        border-radius: 9999px;
        background: var(--red);
        color: #fff;
        font-size: 15px;
        font-weight: 400;
        text-decoration: none;
    }

    .pet-promo-products {
        position: absolute;
        right: clamp(10px, 3vw, 56px);
        bottom: 0;
        z-index: 2;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 22px;
        width: min(40vw, 560px);
        pointer-events: none;
        filter: drop-shadow(0 22px 28px rgba(0, 0, 0, 0.14));
    }

    .pet-promo-bag {
        position: relative;
        z-index: 1;
        width: 234px;
        height: 370px;
        border-radius: 4px 4px 0 0;
        background: linear-gradient(100deg, rgba(255,255,255,0.16), transparent 24%, rgba(0,0,0,0.08) 72%, rgba(255,255,255,0.18)), #f0c51e;
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.16);
        transform: perspective(900px) rotateY(-5deg);
    }

    .pet-promo-bag.small {
        width: 172px;
        height: 265px;
        background-color: #e48a15;
        transform: perspective(900px) rotateY(-6deg);
    }

    .pet-promo-bag.has-image {
        background: transparent !important;
        box-shadow: none;
        transform: none;
        width: min(36vw, 430px);
        height: 330px;
    }

    .pet-promo-bag.has-image::before {
        display: none;
    }

    .pet-promo-bag-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: bottom center;
    }

    .pet-promo-bag::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.08), transparent 18%, transparent 82%, rgba(0,0,0,0.12));
        pointer-events: none;
    }

    .pet-promo-badge {
        position: absolute;
        left: 0;
        top: 0;
        width: 76px;
        height: 76px;
        border-bottom-right-radius: 100%;
        background: #d21b1b;
    }

    .pet-promo-bag.small .pet-promo-badge {
        width: 58px;
        height: 58px;
        background: #6fbd20;
    }

    .pet-promo-logo {
        position: absolute;
        top: 78px;
        left: 50%;
        display: grid;
        width: 70px;
        height: 70px;
        place-items: center;
        transform: translateX(-50%);
        border: 3px solid #173447;
        border-radius: 9999px;
        color: currentColor;
        font-size: 16px;
        font-weight: 900;
    }

    .pet-promo-bag.small .pet-promo-logo {
        top: 58px;
        width: 54px;
        height: 54px;
        font-size: 12px;
    }

    .pet-promo-bag-title {
        position: absolute;
        top: 152px;
        left: 18px;
        right: 18px;
        color: currentColor;
        text-align: center;
        font-size: 38px;
        line-height: 0.94;
        font-weight: 900;
    }

    .pet-promo-bag.small .pet-promo-bag-title {
        top: 116px;
        font-size: 26px;
    }

    .pet-promo-flavor {
        position: absolute;
        top: 224px;
        left: 38px;
        right: 38px;
        border-radius: 9999px;
        background: #bd1b16;
        color: #fff5cb;
        padding: 5px 8px;
        text-align: center;
        font-size: 11px;
        font-weight: 800;
    }

    .pet-promo-bag.small .pet-promo-flavor {
        top: 176px;
        left: 28px;
        right: 28px;
        background: #6fbd20;
        font-size: 8px;
    }

    .pet-promo-window {
        position: absolute;
        left: 70px;
        right: 70px;
        bottom: 54px;
        height: 78px;
        border-radius: 6px;
        background: repeating-linear-gradient(-14deg, #29343a 0 8px, #465158 8px 16px);
        border: 4px solid rgba(255,255,255,0.45);
    }

    .pet-promo-bag.small .pet-promo-window {
        left: 52px;
        right: 52px;
        bottom: 38px;
        height: 54px;
        background: repeating-linear-gradient(-14deg, #d8c58d 0 8px, #b6903d 8px 16px);
    }

    .overlay-card {
        position: relative;
        overflow: hidden;
        color: #fff;
        border-radius: 10px;
    }

    .overlay-card-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .overlay-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.48);
        z-index: 1;
    }

    .overlay-card-content {
        position: relative;
        z-index: 2;
        padding: 16px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .overlay-card-title {
        font-size: clamp(1.2rem, 1.4vw, 1.85rem);
        line-height: 1.2;
        font-weight: 800;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.65);
        max-width: 90%;
    }

    .overlay-card-link {
        margin-top: 8px;
        color: #fff;
        text-decoration: underline;
        font-size: 14px;
        font-weight: 600;
        text-shadow: 0 1px 6px rgba(0, 0, 0, 0.75);
    }

    .section-title {
        font-size: clamp(1.375rem, 2.2vw, 1.875rem);
        font-weight: 700;
        margin: 0;
    }

    .hero-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        grid-template-rows: 220px 220px 180px;
    }

    .hero-promo { grid-column: 1 / span 6; grid-row: 1 / span 2; }
    .hero-newsletter { grid-column: 7 / span 3; grid-row: 1; }
    .hero-trust { grid-column: 7 / span 3; grid-row: 2 / span 2; }
    .hero-ai { grid-column: 10 / span 3; grid-row: 1 / span 2; }
    .hero-office { grid-column: 10 / span 3; grid-row: 3; }
    .hero-sticker { grid-column: 1 / span 3; grid-row: 3; }
    .hero-quote { grid-column: 4 / span 3; grid-row: 3; }

    .thumb-grid-5 {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(5, minmax(0, 1fr));
    }

    .top-categories-slider {
        position: relative;
    }

    .top-categories-row {
        display: flex;
        gap: 14px;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }

    .top-categories-row::-webkit-scrollbar {
        display: none;
    }

    .top-categories-row .top-category-card {
        flex: 0 0 calc((100% - 56px) / 5);
        min-width: 0;
        scroll-snap-align: start;
    }

    .top-categories-arrow {
        position: absolute;
        top: 50%;
        z-index: 3;
        display: inline-flex;
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        transform: translateY(-50%);
        border: 1px solid #e1e1e1;
        border-radius: 9999px;
        background: #fff;
        color: #1f2937;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.16);
    }

    .top-categories-arrow:disabled {
        opacity: 0.4;
        cursor: default;
    }

    .top-categories-arrow[hidden] {
        display: none !important;
    }

    .top-categories-arrow.left { left: -16px; }
    .top-categories-arrow.right { right: -16px; }

    .thumb-grid-4 {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .top-category-card {
        position: relative;
        display: block;
        height: 180px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #cbcbcb;
        background: #d8d8d8;
    }

    .top-category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .top-category-card::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        height: 64px;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.52), rgba(0, 0, 0, 0));
        pointer-events: none;
    }

    .top-category-label {
        position: absolute;
        top: 12px;
        left: 18px;
        z-index: 2;
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        line-height: 1.05;
        text-shadow: 0 1px 8px rgba(0, 0, 0, 0.55);
    }

    .product-showcase-card {
        display: flex;
        flex-direction: column;
        border: 1px solid #cecece;
        border-radius: 8px;
        background: #fff;
        padding: 10px;
        min-height: 348px;
        text-decoration: none;
        color: inherit;
    }

    .product-showcase-media {
        height: 265px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        overflow: hidden;
    }

    .product-showcase-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-showcase-title {
        margin-top: 10px;
        font-size: 15px;
        line-height: 1.12;
        font-weight: 500;
        color: #1f1f1f;
    }

    .product-showcase-description {
        margin-top: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 13px;
        line-height: 1.35;
        color: #5f6368;
    }

    .top-picks-section {
        position: relative;
        margin-top: 40px;
        padding: 28px 0 34px;
    }

    .top-picks-inner {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .top-picks-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 28px;
    }

    .top-picks-tabs {
        display: flex;
        flex: 1;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .top-picks-pill {
        display: inline-flex;
        min-height: 34px;
        align-items: center;
        gap: 8px;
        border: 1px solid #e1e1e1;
        border-radius: 9999px;
        background: #fff;
        padding: 0 22px;
        color: inherit;
        font-size: 16px;
        font-weight: 700;
        white-space: nowrap;
    }

    .top-picks-pill.active {
        border-color: var(--red);
        background: var(--red);
        color: #fff;
    }

    .top-picks-title {
        margin: 0;
        font-size: clamp(1.375rem, 2vw, 1.875rem);
        line-height: 1.2;
        font-weight: 700;
        color: inherit;
    }

    .top-picks-view-all {
        display: inline-flex;
        min-height: 34px;
        align-items: center;
        gap: 8px;
        color: inherit;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .top-picks-view-all:hover {
        color: #b10f1e;
    }

    .top-picks-row {
        display: flex;
        gap: 18px;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
    }

    .top-picks-row::-webkit-scrollbar {
        display: none;
    }

    .top-pick-card {
        display: flex;
        flex-direction: column;
        flex: 0 0 calc((100% - 54px) / 4);
        min-width: 0;
        min-height: 0;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        background: #fff;
        color: #111;
        text-decoration: none;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.02);
        scroll-snap-align: start;
    }

    .top-pick-media {
        position: relative;
        display: flex;
        aspect-ratio: 1 / 1;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border-radius: 20px 20px 0 0;
        background: #eeeeee;
    }

    .top-pick-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .top-pick-badge {
        position: absolute;
        left: 15px;
        top: 14px;
        z-index: 1;
        border-radius: 10px;
        background: #fff;
        padding: 9px 14px;
        color: #4a4a4a;
        font-size: 12px;
        line-height: 1.15;
        font-weight: 700;
    }

    .top-pick-body {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 23px 20px 20px;
    }

    .top-pick-name {
        margin: 0;
        min-height: 22px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 16px;
        line-height: 1.25;
        font-weight: 700;
    }

    .top-pick-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: auto;
        padding-top: 0;
    }

    .top-pick-price-wrap {
        display: flex;
        flex: 1 1 auto;
        min-width: 0;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 10px;
        white-space: normal;
    }

    .top-pick-currency {
        display: inline;
        color: #ff0037;
        font-size: 16px;
        font-weight: 800;
        line-height: 1;
    }

    .top-pick-price {
        display: inline;
        color: #ff0037;
        font-size: 18px;
        line-height: 1;
        font-weight: 900;
    }

    .top-pick-usd {
        color: #7f8794;
        font-size: 15px;
        line-height: 1;
        font-weight: 500;
    }

    .top-pick-order {
        display: inline-flex;
        flex: 0 0 auto;
        min-height: 40px;
        align-items: center;
        justify-content: center;
        border: 1px solid #111;
        border-radius: 10px;
        padding: 0 17px;
        color: #111;
        font-size: 16px;
        font-weight: 700;
        white-space: nowrap;
    }

    .top-pick-card:hover .top-pick-order {
        background: #111;
        color: #fff;
    }

    .featured-products-section {
        margin-top: 24px;
        padding: 28px 16px 34px;
    }

    .featured-products-inner {
        max-width: 1240px;
        margin: 0 auto;
    }

    .featured-products-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
    }

    .featured-product-card {
        display: flex;
        min-width: 0;
        min-height: 0;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        background: #fff;
        color: #111;
        text-decoration: none;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.02);
    }

    .featured-product-card:hover .top-pick-order {
        background: #111;
        color: #fff;
    }

    .featured-product-card .top-pick-media {
        flex: 0 0 auto;
    }

    .top-picks-arrow {
        display: none;
        position: absolute;
        top: 50%;
        z-index: 2;
        width: 46px;
        height: 46px;
        align-items: center;
        justify-content: center;
        transform: translateY(-15%);
        border: 1px solid #e1e1e1;
        border-radius: 9999px;
        background: #fff;
        color: #2f5f9c;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .top-picks-arrow.left {
        left: -24px;
    }

    .top-picks-arrow.right {
        right: -24px;
    }

    .thumb-grid-3 {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .icon-grid-5 {
        display: grid;
        gap: 12px;
        grid-template-columns: repeat(5, minmax(0, 1fr));
    }

    .favourite-product-icon {
        display: flex;
        width: 44px;
        min-height: 44px;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: #111;
        font-size: 20px;
        font-weight: 800;
        line-height: 1;
    }

    .favourite-product-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-card {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .service-card-image {
        height: 210px;
        width: 100%;
        object-fit: cover;
    }

    .service-card-body {
        padding: 16px;
    }

    .service-card-small {
        color: #5e5e5e;
        font-size: 14px;
        line-height: 1.35;
    }

    .service-card-title {
        margin-top: 4px;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.28;
    }

    .home-faq-section {
        width: 100vw;
        margin: 16px calc(50% - 50vw) 0;
        padding: 18px 16px 22px;
        background: #fff;
        color: #171717;
    }

    .home-faq-inner {
        max-width: 1240px;
        margin: 0 auto;
    }

    .home-faq-eyebrow {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        color: #ff1744;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.14em;
        line-height: 1.3;
        text-transform: uppercase;
    }

    .home-faq-eyebrow::before {
        width: 24px;
        height: 2px;
        background: currentColor;
        content: "";
    }

    .home-faq-title {
        margin: 0;
        font-size: clamp(1.45rem, 2vw, 1.75rem);
        line-height: 1.2;
        font-weight: 700;
    }

    .home-faq-subtitle {
        margin-top: 6px;
        color: #55545b;
        font-size: 14px;
        line-height: 1.5;
    }

    .home-faq-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-items: start;
        gap: 12px 16px;
        margin-top: 22px;
    }

    .home-faq-item {
        overflow: hidden;
        border: 1px solid #e7e7eb;
        border-radius: 16px;
        background: #fff;
    }

    .home-faq-question {
        display: flex;
        min-height: 62px;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 18px;
        color: #222126;
        cursor: pointer;
        font-size: 15px;
        line-height: 1.45;
        list-style: none;
    }

    .home-faq-question::-webkit-details-marker { display: none; }

    .home-faq-chevron {
        flex: 0 0 auto;
        width: 18px;
        height: 18px;
        color: #ff1744;
        transition: transform 160ms ease;
    }

    .home-faq-item[open] .home-faq-chevron { transform: rotate(180deg); }

    .home-faq-answer {
        padding: 0 18px 16px;
        color: #55545b;
        font-size: 14px;
        line-height: 1.65;
    }

    .home-coverage-section {
        width: 100vw;
        margin: 12px calc(50% - 50vw) 0;
        padding: 18px 16px 22px;
        background: #fff;
    }

    .home-coverage-inner {
        max-width: 1240px;
        margin: 0 auto;
    }

    .home-coverage-eyebrow {
        margin: 0 0 8px;
        color: #4b6b94;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.28em;
        line-height: 1.4;
        text-transform: uppercase;
    }

    .home-coverage-title {
        margin: 0;
        font-size: clamp(1.65rem, 2.6vw, 2.3rem);
        font-weight: 500;
        line-height: 1.15;
    }

    .home-coverage-viewport {
        margin-top: 26px;
        overflow: visible;
    }

    .home-coverage-track,
    .home-coverage-group {
        display: flex;
        align-items: center;
    }

    .home-coverage-track {
        width: 100%;
    }

    .home-coverage-group {
        width: 100%;
        flex-wrap: wrap;
        gap: 14px;
    }

    .home-coverage-chip {
        display: inline-flex;
        min-height: 46px;
        align-items: center;
        gap: 9px;
        border-radius: 9999px;
        background: #ed1b2f;
        padding: 0 18px;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
    }

    .home-coverage-chip:nth-child(9n + 1) { background: #f8dfe2; color: #8e1b2b; }
    .home-coverage-chip:nth-child(9n + 8) { background: #e54455; }

    .home-coverage-dot {
        width: 8px;
        height: 8px;
        flex: 0 0 auto;
        border-radius: 50%;
        background: #91c96a;
    }

    .starlink-friday-banner {
        display: grid;
        grid-template-columns: minmax(500px, 0.95fr) minmax(360px, 1fr) minmax(210px, auto);
        align-items: center;
        gap: clamp(24px, 3vw, 48px);
        min-height: 100px;
        margin: 0 0 28px;
        overflow: hidden;
        border-radius: 46px;
        background: #002d45;
        padding: 10px clamp(28px, 2.6vw, 42px) 10px 30px;
        color: #fff;
        text-decoration: none;
    }

    .starlink-friday-sale {
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
        max-width: 100%;
        color: var(--red);
        font-weight: 900;
        text-transform: uppercase;
    }

    .starlink-friday-tag {
        width: clamp(42px, 4.8vw, 58px);
        height: clamp(42px, 4.8vw, 58px);
        flex: 0 0 auto;
        color: var(--red);
    }

    .starlink-friday-label {
        display: grid;
        grid-template-columns: minmax(170px, max-content) max-content max-content;
        align-items: center;
        gap: 8px;
        min-width: 0;
        line-height: 0.92;
    }

    .starlink-friday-black {
        font-size: clamp(1.5rem, 2.3vw, 2.6rem);
        letter-spacing: 0.02em;
        overflow-wrap: anywhere;
    }

    .starlink-friday-day {
        display: block;
    }

    .starlink-friday-percent {
        color: #fff;
        font-size: clamp(3rem, 5.5vw, 5.35rem);
        line-height: 0.8;
    }

    .starlink-friday-off {
        font-size: clamp(1.15rem, 1.85vw, 2.1rem);
    }

    .starlink-friday-copy {
        margin: 0;
        max-width: 620px;
        font-size: 15px;
        line-height: 1.45;
        font-weight: 300;
        text-align: center;
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .starlink-friday-button {
        display: inline-flex;
        min-width: 172px;
        min-height: 52px;
        align-items: center;
        justify-content: center;
        justify-self: end;
        border-radius: 999px;
        background: var(--red);
        padding: 0 24px;
        color: #fff;
        font-size: 16px;
        font-weight: 900;
        white-space: nowrap;
    }

    @media (max-width: 1200px) {
        .hero-grid {
            grid-template-rows: 200px 200px 165px;
        }

        .starlink-friday-banner {
            grid-template-columns: 1fr auto;
            border-radius: 32px;
            padding: 20px;
        }

        .starlink-friday-sale {
            grid-column: 1 / -1;
        }

        .starlink-friday-copy {
            max-width: none;
            text-align: left;
        }

        .starlink-friday-button {
            justify-self: start;
        }
    }

    @media (max-width: 1024px) {
        .pet-promo-slide {
            min-height: 520px;
        }

    .pet-promo-dog {
        width: 48vw;
        height: 56%;
    }

        .pet-promo-content {
            width: min(92vw, 660px);
            margin-left: auto;
            padding-top: 56px;
        }

        .pet-promo-products {
            right: 10px;
            gap: 12px;
            width: min(48vw, 430px);
        }

        .pet-promo-bag {
            width: 170px;
            height: 270px;
        }

        .pet-promo-bag.small {
            width: 128px;
            height: 200px;
        }

        .pet-promo-bag.has-image {
            width: min(44vw, 360px);
            height: 260px;
        }

        .pet-promo-bag-title {
            top: 116px;
            font-size: 27px;
        }

        .pet-promo-bag.small .pet-promo-bag-title {
            top: 90px;
            font-size: 19px;
        }

        .pet-promo-flavor {
            top: 172px;
            left: 28px;
            right: 28px;
            font-size: 8px;
        }

        .pet-promo-window {
            left: 50px;
            right: 50px;
            bottom: 38px;
            height: 54px;
        }

        .hero-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            grid-template-rows: auto;
        }

        .hero-promo,
        .hero-newsletter,
        .hero-trust,
        .hero-ai,
        .hero-office,
        .hero-sticker,
        .hero-quote { grid-column: auto; grid-row: auto; }

        .thumb-grid-5,
        .icon-grid-5 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .thumb-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .thumb-grid-3 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .top-categories-row .top-category-card { flex-basis: calc((100% - 28px) / 3); }
        .home-faq-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .featured-products-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .top-pick-card { flex-basis: calc((100% - 36px) / 3); }
        .top-pick-footer {
            align-items: stretch;
            flex-direction: column;
            gap: 12px;
        }
        .top-pick-order {
            width: 100%;
        }

    }

    @media (max-width: 640px) {
        .pet-promo-slide {
            min-height: 640px;
        }

        .pet-promo-dog {
            left: -18%;
            width: 76vw;
            height: 38%;
        }

        .pet-promo-content {
            width: min(92vw, 660px);
            margin-left: auto;
            padding-top: 38px;
        }

        .pet-promo-icons {
            font-size: 23px;
        }

        .pet-promo-title {
            margin-top: 22px;
            font-size: clamp(2.15rem, 12vw, 3.3rem);
        }

        .pet-promo-subtitle {
            font-size: clamp(1.2rem, 6vw, 1.7rem);
        }

        .pet-promo-copy {
            max-width: 300px;
        }

        .pet-promo-button {
            min-width: 176px;
            height: 54px;
        }

        .pet-promo-products {
            right: -18px;
            width: min(72vw, 330px);
        }

        .pet-promo-bag {
            width: 138px;
            height: 220px;
        }

        .pet-promo-bag.small {
            width: 104px;
            height: 162px;
        }

        .pet-promo-bag.has-image {
            width: min(66vw, 280px);
            height: 210px;
        }

        .thumb-grid-5,
        .thumb-grid-4,
        .thumb-grid-3,
        .icon-grid-5 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .top-categories-row { gap: 12px; }
        .top-categories-row .top-category-card { flex-basis: 78%; }
        .top-categories-arrow { width: 36px; height: 36px; }
        .top-categories-arrow.left { left: 6px; }
        .top-categories-arrow.right { right: 6px; }

        .home-faq-section { margin-top: 12px; padding: 14px 16px 18px; }
        .home-faq-eyebrow { margin-bottom: 8px; font-size: 10px; }
        .home-faq-subtitle { margin-top: 5px; font-size: 13px; }
        .home-faq-grid { grid-template-columns: 1fr; gap: 10px; margin-top: 18px; }
        .home-faq-question { min-height: 54px; padding: 12px 14px; font-size: 14px; }
        .home-faq-answer { padding: 0 14px 14px; font-size: 13px; }
        .home-coverage-section { margin-top: 10px; padding: 14px 16px 18px; }
        .home-coverage-eyebrow { font-size: 9px; letter-spacing: 0.18em; }
        .home-coverage-title { font-size: clamp(1.4rem, 7vw, 1.8rem); }
        .home-coverage-viewport { margin-top: 18px; }
        .home-coverage-group { gap: 8px; }
        .home-coverage-chip { min-height: 38px; gap: 7px; padding: 0 14px; font-size: 12px; }
        .home-coverage-dot { width: 7px; height: 7px; }

        .thumb-grid-4 {
            gap: 16px;
        }

        .product-showcase-card {
            min-height: 0;
            padding: 8px;
        }

        .product-showcase-media {
            height: auto;
            aspect-ratio: 1 / 1;
            border-radius: 7px;
            background: #f7f7f7;
        }

        .product-showcase-title {
            margin-top: 12px;
            min-height: 38px;
            font-size: 16px;
            line-height: 1.15;
        }

        .product-showcase-description {
            margin-top: 6px;
            font-size: 14px;
            line-height: 1.3;
        }

        .service-card {
            height: 100%;
        }

        .service-card-image {
            height: 150px;
        }

        .service-card-body {
            padding: 12px;
        }

        .service-card-small {
            font-size: 13px;
            line-height: 1.32;
        }

        .service-card-title {
            font-size: 18px;
            line-height: 1.18;
        }

        .top-picks-row {
            gap: 12px;
        }

        .top-pick-card { flex-basis: calc((100% - 12px) / 2); }

        .featured-products-section {
            margin-top: 12px;
            padding: 20px 16px 28px;
        }

        .featured-products-grid { gap: 12px; }

        .featured-product-card {
            border-radius: 14px;
        }

        .featured-product-card .top-pick-media {
            border-radius: 14px 14px 0 0;
        }

        .top-pick-card {
            border-radius: 14px;
        }

        .top-pick-media {
            border-radius: 14px 14px 0 0;
        }

        .top-pick-name {
            display: -webkit-box;
            min-height: 34px;
            overflow: hidden;
            white-space: normal;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            font-size: 14px;
            line-height: 1.2;
        }

        .top-pick-body {
            padding: 12px;
        }

        .top-pick-footer {
            align-items: stretch;
            flex-direction: column;
            gap: 10px;
            margin-top: auto;
        }

        .top-pick-price-wrap {
            flex-wrap: wrap;
            gap: 4px 7px;
            white-space: normal;
        }

        .top-pick-currency {
            font-size: 13px;
        }

        .top-pick-price {
            font-size: 16px;
        }

        .top-pick-usd {
            font-size: 12px;
        }

        .top-pick-order {
            width: 100%;
            min-height: 34px;
            padding: 0 10px;
            font-size: 13px;
        }

        .top-pick-badge {
            left: 10px;
            top: 10px;
            max-width: calc(100% - 20px);
            padding: 7px 10px;
            font-size: 11px;
        }

        .top-picks-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .top-picks-tabs {
            justify-content: flex-start;
            overflow-x: auto;
            width: 100%;
            padding-bottom: 2px;
        }

        .top-picks-pill {
            min-height: 32px;
            padding: 0 14px;
            font-size: 13px;
        }

        .top-picks-view-all {
            min-height: 38px;
            font-size: 15px;
        }

        .starlink-friday-banner {
            grid-template-columns: 1fr;
            gap: 14px;
            border-radius: 24px;
            padding: 22px 18px;
        }

        .starlink-friday-sale {
            justify-content: center;
            gap: 12px;
        }

        .starlink-friday-label {
            grid-template-columns: auto auto auto;
            gap: 6px;
        }

        .starlink-friday-copy {
            font-size: 15px;
            text-align: center;
        }

        .starlink-friday-button {
            justify-self: center;
            min-width: 168px;
        }
    }
    .pet-promo-slide:has(.promo-gallery) .pet-promo-content { width: 34%; margin-left: 28%; margin-right: 38%; }
    .promo-gallery { position: absolute; top: 24px; bottom: 24px; right: 2%; z-index: 4; width: 35%; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 18px; overflow: hidden; border-radius: 18px; }
    .promo-column { min-width: 0; overflow: hidden; }
    .promo-track { animation: promo-up 36s linear infinite; }
    .promo-track.is-down { animation-direction: reverse; }
    .promo-group { display: grid; gap: 18px; padding-bottom: 18px; }
    .promo-tile { position: relative; display: block; aspect-ratio: 1; overflow: hidden; border-radius: 16px; background: #edd4c5; color: white; text-decoration: none; }
    .promo-tile img { width: 100%; height: 100%; object-fit: cover; }
    .promo-tile > span { position: absolute; inset: 0; display: flex; align-items: flex-end; justify-content: center; gap: 5px; padding: 16px 10px; background: linear-gradient(transparent 55%,rgba(0,0,0,.12) 72%,rgba(0,0,0,.48)); text-shadow: none; color: #fff; font-size: clamp(12px,1vw,16px); font-weight: 700; line-height: 1.3; text-align: center; }
    .promo-tile:focus-visible { outline: 3px solid white; outline-offset: -4px; }
    .promo-gallery:is(:hover,:focus-within,.is-touching) .promo-track { animation-play-state: paused; }
    @keyframes promo-up { from { transform: translateY(0); } to { transform: translateY(-50%); } }
    @media(max-width:900px) {
        .pet-promo-slide:has(.promo-gallery) .pet-promo-content { width: 54%; margin-left: 0; margin-right: 46%; padding-inline: 20px; }
        .promo-gallery { width: 43%; gap: 12px; }
        .pet-promo-slide:has(.promo-gallery) .pet-promo-dog { opacity: .2; }
    }
    @media(max-width:640px) {
        .pet-promo-slide:has(.promo-gallery) .pet-promo-content { width: 100%; margin: 0; padding-bottom: 28px; }
        .promo-gallery { position: relative; top: auto; right: auto; bottom: auto; width: calc(100% - 32px); height: 340px; margin: 0 16px 24px; }
        .promo-tile > span { font-size: 13px; }
    }
    @media(prefers-reduced-motion:reduce) {
        .promo-track { animation: none; }
        .promo-column { overflow-y: auto; }
        .promo-group[aria-hidden] { display: none; }
    }
</style>
@endpush

@section('content')
@php
    $sectionMap = collect($homeSections ?? [])
        ->filter(fn ($section) => is_array($section) && ! empty($section['component']))
        ->keyBy('component');
    $publicAsset = fn ($url) => \App\Support\PublicAssetUrl::resolve($url) ?? '';
    $displayImage = fn ($url, int $width = 640, int $quality = 66) => \App\Support\PublicAssetUrl::image($url, $width, $quality) ?? '';

    $heroMain = $sectionMap->get('home_hero_main', []);
    $heroNewsletter = $sectionMap->get('home_hero_newsletter', []);
    $heroTrust = $sectionMap->get('home_hero_trust', []);
    $heroAi = $sectionMap->get('home_hero_ai', []);
    $heroOffice = $sectionMap->get('home_hero_office', []);
    $heroSticker = $sectionMap->get('home_hero_sticker', []);
    $heroQuote = $sectionMap->get('home_hero_quote', []);
    $showHeroMain = $sectionMap->has('home_hero_main');
    $showHeroNewsletter = $sectionMap->has('home_hero_newsletter');
    $showHeroTrust = $sectionMap->has('home_hero_trust');
    $showHeroAi = $sectionMap->has('home_hero_ai');
    $showHeroOffice = $sectionMap->has('home_hero_office');
    $showHeroSticker = $sectionMap->has('home_hero_sticker');
    $showHeroQuote = $sectionMap->has('home_hero_quote');
    $homeHandledComponents = [
        'home_pet_promo',
        'home_hero_main',
        'home_hero_newsletter',
        'home_hero_trust',
        'home_hero_ai',
        'home_hero_office',
        'home_hero_sticker',
        'home_hero_quote',
        'home_top_categories',
        'home_product_tiles',
        'home_top_picks',
        'home_services',
        'home_faq',
        'home_coverage',
        'home_favourites',
    ];
    $genericHomeSections = collect($homeSections ?? [])
        ->filter(fn ($section) => is_array($section) && ! in_array(data_get($section, 'component'), $homeHandledComponents, true))
        ->values();
    $petPromoSection = $sectionMap->get('home_pet_promo')
        ?? collect(\App\Support\HomepageContentDefaults::sections())->firstWhere('component', 'home_pet_promo')
        ?? [];
    $petPromoItems = collect(data_get($petPromoSection, 'items', []))->values();
    $petPromoTitle = trim((string) data_get($petPromoSection, 'title', 'Online printing'));
    $petPromoTitleParts = preg_split('/\s+/', $petPromoTitle, 2) ?: ['Online', 'printing'];
    $petPromoTitleAccent = $petPromoTitleParts[0] ?? '';
    $petPromoTitleRest = $petPromoTitleParts[1] ?? '';
    $petPromoSubtitle = data_get($petPromoSection, 'subtitle', 'With Nai Print');
    $petPromoCopy = data_get($petPromoSection, 'content', 'Ideal for business cards, flyers, banners, and branded apparel with nationwide delivery');
    $petPromoButtonLabel = data_get($petPromoSection, 'button_label', 'Print Now');
    $petPromoButtonUrl = data_get($petPromoSection, 'button_url', route('products.index'));
    $petPromoDogImage = data_get($petPromoSection, 'image_url', asset('storage/media-library/pet-promo-dog.jpg'));
    $petPromoBackgroundImage = data_get($petPromoSection, 'bg_image_url', '');
    $petPromoBackgroundImageUrl = $displayImage($petPromoBackgroundImage, 1600, 70);
    $petPromoBgColor = data_get($petPromoSection, 'bg_color', '#f7a9b7');
    $petPromoTextColor = data_get($petPromoSection, 'text_color', '#00304a');
    $petPromoAccentColor = '#b10f1e';
    $hexToRgb = function (?string $hex): ?array {
        $hex = trim((string) $hex);

        if (! preg_match('/^#?([0-9a-f]{3}|[0-9a-f]{6})$/i', $hex, $matches)) {
            return null;
        }

        $value = $matches[1];

        if (strlen($value) === 3) {
            $value = $value[0] . $value[0] . $value[1] . $value[1] . $value[2] . $value[2];
        }

        return [
            hexdec(substr($value, 0, 2)),
            hexdec(substr($value, 2, 2)),
            hexdec(substr($value, 4, 2)),
        ];
    };
    $relativeLuminance = function (array $rgb): float {
        $channels = array_map(function (int $value): float {
            $channel = $value / 255;

            return $channel <= 0.03928
                ? $channel / 12.92
                : (($channel + 0.055) / 1.055) ** 2.4;
        }, $rgb);

        return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
    };
    $contrastRatio = function (?string $foreground, ?string $background) use ($hexToRgb, $relativeLuminance): ?float {
        $foregroundRgb = $hexToRgb($foreground);
        $backgroundRgb = $hexToRgb($background);

        if (! $foregroundRgb || ! $backgroundRgb) {
            return null;
        }

        $foregroundLuminance = $relativeLuminance($foregroundRgb);
        $backgroundLuminance = $relativeLuminance($backgroundRgb);
        $light = max($foregroundLuminance, $backgroundLuminance);
        $dark = min($foregroundLuminance, $backgroundLuminance);

        return ($light + 0.05) / ($dark + 0.05);
    };
    $bestReadableColor = function (string $background, array $options) use ($contrastRatio): string {
        return collect($options)
            ->sortByDesc(fn (string $color) => $contrastRatio($color, $background) ?? 0)
            ->first();
    };

    if (($contrastRatio($petPromoTextColor, $petPromoBgColor) ?? 4.5) < 4.5) {
        $petPromoTextColor = $bestReadableColor($petPromoBgColor, ['#ffffff', '#002033']);
    }

    if (($contrastRatio($petPromoAccentColor, $petPromoBgColor) ?? 3) < 3) {
        $petPromoAccentColor = $bestReadableColor($petPromoBgColor, ['#ff3347', '#b10f1e']);
    }

    $petPromoBackgroundStyle = 'background: ' . $petPromoBgColor;
    if ($petPromoBackgroundImageUrl) {
        $petPromoBackgroundStyle .= " url('{$petPromoBackgroundImageUrl}') center / cover no-repeat";
    }

    $topCategoriesSection = $sectionMap->get('home_top_categories', []);
    $topCategories = collect(data_get($topCategoriesSection, 'items', []))->map(fn ($item) => [
        'name' => data_get($item, 'title', ''),
        'image' => data_get($item, 'image_url', ''),
        'url' => data_get($item, 'link_url', route('products.index')),
    ])->filter(fn ($item) => ! empty($item['name']))->values();

    if ($topCategories->isEmpty()) {
        $topCategories = collect($categories ?? [])->take(5)->map(fn ($category) => [
            'name' => $category->name,
            'image' => $category->image ?: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=70',
            'url' => route('products.index', ['category' => $category->slug]),
        ]);
    }

    $productTilesSection = $sectionMap->get('home_product_tiles', []);
    $productTiles = collect(data_get($productTilesSection, 'items', []))->map(fn ($item) => [
        'name' => data_get($item, 'title', ''),
        'description' => data_get($item, 'content') ?: data_get($item, 'subtitle', ''),
        'image' => data_get($item, 'image_url', ''),
        'url' => data_get($item, 'link_url', route('products.index')),
    ])->filter(fn ($item) => ! empty($item['name']))->values();

    if ($productTiles->isEmpty()) {
        $productTiles = collect($featuredProducts ?? [])->take(4)->map(fn ($product) => [
            'name' => $product->name,
            'description' => $product->short_description ?: $product->description,
            'image' => $product->thumbnail ?: 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=1200&q=70',
            'url' => route('products.show', $product),
        ]);
    }

    $topPickUsdRate = (float) config('shop.usd_exchange_rate', 126);
    $topPicks = collect($topPickProducts ?? [])->map(function ($product) use ($displayImage, $topPickUsdRate) {
        $displayPrice = (float) $product->base_price;

        $price = number_format($displayPrice, 0);
        $usdPrice = '$' . number_format($displayPrice / max($topPickUsdRate, 1), 2);

        return [
        'name' => $product->name,
        'image' => $displayImage($product->thumbnail, 420, 64) ?: asset('favicon.svg'),
        'price' => $price,
        'usd_price' => $usdPrice,
        'url' => route('products.show', $product),
        'category' => $product->category?->name ?: $product->categories->first()?->name ?: 'Products',
        ];
    })->take(8)->values();

    $topPicksSection = $sectionMap->get('home_top_picks', []);
    $topPicksTabs = collect(data_get($topPicksSection, 'items', []))
        ->filter(fn ($item) => is_array($item) && data_get($item, 'title'))
        ->values();

    $servicesSection = $sectionMap->get('home_services', []);
    $services = collect(data_get($servicesSection, 'items', []))->map(fn ($item) => [
        'small' => data_get($item, 'subtitle', ''),
        'title' => data_get($item, 'title', ''),
        'image' => data_get($item, 'image_url', ''),
        'url' => data_get($item, 'link_url', route('products.index')),
    ])->filter(fn ($item) => ! empty($item['title']))->values();

    $faqSection = $sectionMap->get('home_faq', []);
    $faqItems = collect(data_get($faqSection, 'items', []))
        ->filter(fn ($item) => is_array($item) && data_get($item, 'title'))
        ->values();

    $coverageSection = $sectionMap->get('home_coverage', []);
    $coverageCounties = collect(data_get($coverageSection, 'items', []))
        ->filter(fn ($item) => is_array($item) && data_get($item, 'title'))
        ->values();

    $favouritesSection = $sectionMap->get('home_favourites', []);
    $favouriteProducts = collect(data_get($favouritesSection, 'items', []))->map(fn ($item) => [
        'name' => data_get($item, 'title', ''),
        'icon' => data_get($item, 'image_url', ''),
        'url' => data_get($item, 'link_url', route('products.index')),
    ])->filter(fn ($item) => $item['name'] !== '')->values();

    if ($favouriteProducts->isEmpty()) {
        $favouriteProducts = collect($categories ?? [])->take(5)->map(fn ($category) => [
            'name' => $category->name,
            'icon' => $category->icon ?: $category->image,
            'url' => route('products.index', ['category' => $category->slug]),
        ]);
    }
@endphp

<section class="pet-promo-slide" aria-label="{{ $petPromoSubtitle ?: $petPromoTitle }}">
    <div
        class="pet-promo-background"
        style="{{ $petPromoBackgroundStyle }};"
        aria-hidden="true"
    ></div>
    <img loading="eager" fetchpriority="high" decoding="async"
        src="{{ $displayImage($petPromoDogImage, 900, 70) }}"
        class="pet-promo-dog"
        alt="{{ data_get($petPromoSection, 'title', 'Happy dog') }}"
    >
    <div class="pet-promo-content" style="color: {{ $petPromoTextColor }}; --pet-promo-accent: {{ $petPromoAccentColor }};">
        <div class="pet-promo-icons">▼ · ♣ · ▼</div>
        <h2 class="pet-promo-title">
            @if($petPromoTitleAccent)
                <span class="pet-promo-title-accent">{{ $petPromoTitleAccent }}</span>{{ $petPromoTitleRest ? ' ' . $petPromoTitleRest : '' }}
            @else
                Online printing
            @endif
        </h2>
        @if($petPromoSubtitle)
            <div class="pet-promo-subtitle">{!! nl2br(e($petPromoSubtitle)) !!}</div>
        @endif
        @if($petPromoCopy)
            <p class="pet-promo-copy">{!! nl2br(e($petPromoCopy)) !!}</p>
        @endif
        @if($petPromoButtonLabel && $petPromoButtonUrl)
            <a href="{{ $petPromoButtonUrl }}" class="pet-promo-button">{{ $petPromoButtonLabel }}</a>
        @endif
    </div>
    @if($sliderProducts->isNotEmpty())
    <div class="promo-gallery" aria-label="Explore products">
        @foreach([0, 1] as $column)
            @php
                $items = $sliderProducts->filter(fn ($product, $index) => $index % 2 === $column)->values();
                if ($items->isEmpty()) $items = $sliderProducts->values();
                while ($items->count() < 4) $items = $items->concat($items)->values();
            @endphp
            <div class="promo-column">
                <div class="promo-track {{ $column ? 'is-down' : '' }}">
                    @foreach([false, true] as $duplicate)
                        <div class="promo-group" @if($duplicate) aria-hidden="true" inert @endif>
                            @foreach($items as $product)
                                <a class="promo-tile" href="{{ route('products.show', $product) }}" @if($duplicate) tabindex="-1" @endif>
                                    <img src="{{ $displayImage($product->thumbnail, 480, 70) }}" alt="" decoding="async">
                                    <span>{{ $product->name }} <b aria-hidden="true">▷</b></span>
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    @endif
</section>

<section class="home-wrap py-6">
    @if(! empty($cmsHome?->body))
        <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 text-slate-700">
            {!! nl2br(e($cmsHome->body)) !!}
        </section>
    @endif

    @foreach($genericHomeSections as $section)
        @php
            $component = data_get($section, 'component', 'text');
            $title = data_get($section, 'title');
            $subtitle = data_get($section, 'subtitle');
            $content = data_get($section, 'content');
            $image = data_get($section, 'image_url');
            $imageUrl = $displayImage($image, $component === 'hero' ? 1200 : 520, 66);
            $bgColor = data_get($section, 'bg_color', '#ffffff');
            $textColor = data_get($section, 'text_color', '#111111');
            $items = collect(data_get($section, 'items', []))->filter(fn ($item) => is_array($item))->values();
        @endphp

        <section class="mb-6 rounded-2xl border border-slate-200 p-5" style="background: {{ $bgColor }}; color: {{ $textColor }};">
            @if($imageUrl && $component === 'hero')
                <img loading="lazy" decoding="async" src="{{ $imageUrl }}" alt="{{ $title }}" class="mb-5 h-[260px] w-full rounded-xl object-cover">
            @endif
            <div class="grid gap-5 md:grid-cols-[1fr_280px] md:items-start">
                <div>
                    @if($title)
                        <h2 class="section-title">{{ $title }}</h2>
                    @endif
                    @if($subtitle)
                        <p class="mt-1 text-sm font-semibold opacity-80">{{ $subtitle }}</p>
                    @endif
                    @if($content)
                        <p class="mt-3 whitespace-pre-line text-[15px] leading-7 opacity-90">{{ $content }}</p>
                    @endif
                    @if(data_get($section, 'button_label') && data_get($section, 'button_url'))
                        <a href="{{ data_get($section, 'button_url') }}" class="mt-4 inline-flex rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white">{{ data_get($section, 'button_label') }}</a>
                    @endif
                </div>
                @if($imageUrl && $component !== 'hero')
                    <img loading="lazy" decoding="async" src="{{ $imageUrl }}" alt="{{ $title }}" class="h-44 w-full rounded-xl object-cover">
                @endif
            </div>

            @if($items->isNotEmpty())
                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($items as $item)
                        @php
                            $itemImage = data_get($item, 'image_url');
                            $itemImageUrl = $displayImage($itemImage, 420, 64);
                        @endphp
                        <a href="{{ data_get($item, 'link_url', '#') }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white text-slate-900">
                            @if($itemImageUrl)
                                <img loading="lazy" decoding="async" src="{{ $itemImageUrl }}" alt="{{ data_get($item, 'title') }}" class="h-40 w-full object-cover">
                            @endif
                            <div class="p-4">
                                @if(data_get($item, 'title'))
                                    <h3 class="text-lg font-bold">{{ data_get($item, 'title') }}</h3>
                                @endif
                                @if(data_get($item, 'subtitle'))
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500">{{ data_get($item, 'subtitle') }}</p>
                                @endif
                                @if(data_get($item, 'content'))
                                    <p class="mt-2 text-sm text-slate-600">{{ data_get($item, 'content') }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @endforeach

    <section class="mt-12">
        <div class="mb-4 flex items-end justify-between gap-3">
            <h2 class="section-title">{{ data_get($topCategoriesSection, 'title', 'Top categories') }}</h2>
            <a href="{{ data_get($topCategoriesSection, 'button_url', route('products.index')) }}" class="text-sm font-medium text-[#3f5f8e]">{{ data_get($topCategoriesSection, 'button_label', 'SHOW ALL') }}</a>
        </div>
        <div class="top-categories-slider" data-top-categories-slider>
            <button type="button" class="top-categories-arrow left" data-top-categories-prev aria-label="Previous categories">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <div class="top-categories-row" data-top-categories-track>
                @foreach($topCategories as $item)
                    @php
                        $imageLoading = $loop->first ? 'eager' : 'lazy';
                        $imagePriority = $loop->first ? 'high' : 'auto';
                    @endphp
                    <a href="{{ data_get($item, 'url', route('products.index')) }}" class="top-category-card">
                        <img loading="{{ $imageLoading }}" fetchpriority="{{ $imagePriority }}" decoding="async" src="{{ $displayImage($item['image'], 420, 64) }}" alt="{{ $item['name'] }}">
                        <span class="top-category-label">{{ $item['name'] }}</span>
                    </a>
                @endforeach
            </div>
            <button type="button" class="top-categories-arrow right" data-top-categories-next aria-label="Next categories">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
            </button>
        </div>
    </section>

    </section>

<section class="top-picks-section" style="background: {{ data_get($topPicksSection, 'bg_color', '#ffffff') }}; color: {{ data_get($topPicksSection, 'text_color', '#001f3f') }};">
    <div class="top-picks-inner">
        <div class="top-picks-header">
            <h2 class="top-picks-title">{{ data_get($topPicksSection, 'title', 'Best Selling') }}</h2>
            <a href="{{ data_get($topPicksSection, 'button_url', route('products.index')) }}" class="top-picks-view-all">
                {{ data_get($topPicksSection, 'button_label', 'All Products') }}
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
            </a>
        </div>

        <div class="relative" data-top-picks-slider>
            <button type="button" class="top-picks-arrow left" data-top-picks-prev aria-label="Previous top picks">
                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>

            <div class="top-picks-row" data-top-picks-track>
                @foreach($topPicks as $pick)
                    <a href="{{ $pick['url'] }}" class="top-pick-card">
                        <div class="top-pick-media">
                            <span class="top-pick-badge">{{ $pick['category'] }}</span>
                            <img loading="lazy" decoding="async" src="{{ $pick['image'] }}" alt="{{ $pick['name'] }}">
                        </div>
                        <div class="top-pick-body">
                            <h3 class="top-pick-name">{{ $pick['name'] }}</h3>
                            <div class="top-pick-footer">
                                <span class="top-pick-price-wrap">
                                    <span class="top-pick-currency">KES</span>
                                    <span class="top-pick-price">{{ $pick['price'] }}</span>
                                    <span class="top-pick-usd">{{ $pick['usd_price'] }}</span>
                                </span>
                                <span class="top-pick-order">Order Now</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <button type="button" class="top-picks-arrow right" data-top-picks-next aria-label="Next top picks">
                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg>
            </button>
        </div>
    </div>
</section>

<section class="featured-products-section" aria-labelledby="featured-products-title">
    <div class="featured-products-inner">
        <div class="top-picks-header">
            <h2 id="featured-products-title" class="top-picks-title">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="top-picks-view-all">
                All Products
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
            </a>
        </div>

        <div class="featured-products-grid">
            @foreach($featuredProducts->take(8) as $product)
                @php
                    $featuredPrice = (float) $product->base_price;
                    $featuredCategory = $product->category?->name ?: $product->categories->first()?->name ?: 'Products';
                @endphp
                <a href="{{ route('products.show', $product) }}" class="featured-product-card">
                    <div class="top-pick-media">
                        <span class="top-pick-badge">{{ $featuredCategory }}</span>
                        <img loading="lazy" decoding="async" src="{{ $displayImage($product->thumbnail, 420, 64) ?: asset('favicon.svg') }}" alt="{{ $product->name }}">
                    </div>
                    <div class="top-pick-body">
                        <h3 class="top-pick-name">{{ $product->name }}</h3>
                        <div class="top-pick-footer">
                            <span class="top-pick-price-wrap">
                                <span class="top-pick-currency">KES</span>
                                <span class="top-pick-price">{{ number_format($featuredPrice, 0) }}</span>
                                <span class="top-pick-usd">${{ number_format($featuredPrice / max((float) config('shop.usd_exchange_rate', 126), 1), 2) }}</span>
                            </span>
                            <span class="top-pick-order">Order Now</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="home-wrap mt-10 bg-white px-4 py-5">
    <h2 class="section-title mb-4">{{ data_get($productTilesSection, 'title', 'Discover our huge variety of products') }}</h2>
    <div class="thumb-grid-4">
        @foreach($productTiles as $tile)
            @php
                $imageLoading = $loop->first ? 'eager' : 'lazy';
                $imagePriority = $loop->first ? 'high' : 'auto';
            @endphp
            <a href="{{ data_get($tile, 'url', route('products.index')) }}" class="product-showcase-card">
                <div class="product-showcase-media">
                    <img loading="{{ $imageLoading }}" fetchpriority="{{ $imagePriority }}" decoding="async" src="{{ $displayImage($tile['image'], 560, 64) }}" alt="{{ $tile['name'] }}">
                </div>
                <h3 class="product-showcase-title">{{ $tile['name'] }}</h3>
                @if(! empty($tile['description']))
                    <p class="product-showcase-description">{{ strip_tags($tile['description']) }}</p>
                @endif
            </a>
        @endforeach
    </div>
</section>

<section class="home-wrap py-6">
    @if($services->isNotEmpty())
        <section class="mt-10 bg-white px-4 py-5">
            <h2 class="section-title mb-4">{{ data_get($servicesSection, 'title', 'Discover our services') }}</h2>
            <div class="thumb-grid-3">
                @foreach($services as $service)
                    <a href="{{ data_get($service, 'url', route('products.index')) }}" class="home-card service-card">
                        <img loading="lazy" decoding="async" src="{{ $displayImage($service['image'], 560, 64) }}" alt="{{ $service['title'] }}" class="service-card-image">
                        <div class="service-card-body">
                            <p class="service-card-small">{{ $service['small'] }}</p>
                            <h3 class="service-card-title">{{ $service['title'] }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if($faqItems->isNotEmpty())
        <section class="home-faq-section" aria-labelledby="home-faq-title" style="background: {{ data_get($faqSection, 'bg_color', '#ffffff') }}; color: {{ data_get($faqSection, 'text_color', '#171717') }};">
            <div class="home-faq-inner">
                @if(data_get($faqSection, 'eyebrow'))
                    <p class="home-faq-eyebrow">{{ data_get($faqSection, 'eyebrow') }}</p>
                @endif
                <h2 id="home-faq-title" class="home-faq-title">{{ data_get($faqSection, 'title', 'Frequently Asked Questions') }}</h2>
                @if(data_get($faqSection, 'subtitle'))
                    <p class="home-faq-subtitle">{{ data_get($faqSection, 'subtitle') }}</p>
                @endif
                <div class="home-faq-grid">
                    @foreach($faqItems as $item)
                        <details class="home-faq-item">
                            <summary class="home-faq-question">
                                <span>{{ data_get($item, 'title') }}</span>
                                <svg viewBox="0 0 24 24" class="home-faq-chevron" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                            </summary>
                            @if(data_get($item, 'content'))
                                <div class="home-faq-answer">{{ data_get($item, 'content') }}</div>
                            @endif
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($coverageCounties->isNotEmpty())
        <section class="home-coverage-section" aria-labelledby="home-coverage-title" style="background: {{ data_get($coverageSection, 'bg_color', '#ffffff') }}; color: {{ data_get($coverageSection, 'text_color', '#172033') }};">
            <div class="home-coverage-inner">
                @if(data_get($coverageSection, 'eyebrow'))
                    <p class="home-coverage-eyebrow">{{ data_get($coverageSection, 'eyebrow') }}</p>
                @endif
                <h2 id="home-coverage-title" class="home-coverage-title">{{ data_get($coverageSection, 'title', 'Serving all 47 counties of Kenya') }}</h2>
                <div class="home-coverage-viewport" role="group" aria-label="Delivery coverage across Kenya">
                    <div class="home-coverage-track">
                        <div class="home-coverage-group">
                            @foreach($coverageCounties as $county)
                                <span class="home-coverage-chip"><span>{{ data_get($county, 'title') }}</span><span class="home-coverage-dot" aria-hidden="true"></span></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="mt-10 bg-white px-4 py-5">
        <a href="{{ route('products.index', ['q' => 'offset printing']) }}" class="starlink-friday-banner" aria-label="Shop offset printing offers">
            <div class="starlink-friday-sale">
                <svg class="starlink-friday-tag" viewBox="0 0 64 64" aria-hidden="true" fill="currentColor">
                    <path d="M6 7h25.2c1.6 0 3.1.6 4.2 1.8l22.4 22.4a6 6 0 0 1 0 8.5L39.7 57.8a6 6 0 0 1-8.5 0L8.8 35.4A6 6 0 0 1 7 31.2V6z"/>
                    <circle cx="23" cy="23" r="5.2" fill="#002d45"/>
                </svg>
                <span class="starlink-friday-label">
                    <span class="starlink-friday-black">Bulk <span class="starlink-friday-day">Printing</span></span>
                    <span class="starlink-friday-percent">20</span>
                    <span class="starlink-friday-off">%<span class="starlink-friday-day">OFF</span></span>
                </span>
            </div>
            <p class="starlink-friday-copy">Premium offset printing for flyers, brochures, books, magazines, posters and packaging. Sharp colour, bulk-ready quality.</p>
            <span class="starlink-friday-button">Order Prints Now</span>
        </a>

        <h2 class="section-title mb-4">{{ data_get($favouritesSection, 'title', 'Find your favourite product') }}</h2>
        <div class="icon-grid-5">
            @foreach($favouriteProducts as $product)
                @php
                    $productIcon = data_get($product, 'icon', '');
                    $productIconUrl = $productIcon && \Illuminate\Support\Str::startsWith($productIcon, ['http://', 'https://', '/', 'storage/'])
                        ? $displayImage($productIcon, 180, 60)
                        : '';
                @endphp
                <a href="{{ data_get($product, 'url', route('products.index')) }}" class="home-card px-3 py-5 text-center">
                    @if($productIconUrl || $productIcon)
                        <div class="favourite-product-icon">
                            @if($productIconUrl)
                            <img loading="lazy" decoding="async" src="{{ $productIconUrl }}" alt="{{ data_get($product, 'name') }} icon">
                            @else
                                <span>{{ $productIcon }}</span>
                            @endif
                        </div>
                    @endif
                    <div class="mt-3 text-[20px] font-medium">{{ data_get($product, 'name') }}</div>
                </a>
            @endforeach
        </div>
        <div class="mt-4 text-right">
            <a href="{{ data_get($favouritesSection, 'button_url', route('products.index')) }}" class="text-sm text-[var(--red)]">{{ data_get($favouritesSection, 'button_label', 'Show all categories') }}</a>
        </div>
    </section>
</section>

@include('partials.footer-help-request')
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.promo-gallery').forEach((gallery) => {
            gallery.addEventListener('pointerdown', () => gallery.classList.add('is-touching'));
            const resume = () => gallery.classList.remove('is-touching');
            window.addEventListener('pointerup', resume);
            window.addEventListener('pointercancel', resume);
            window.addEventListener('blur', resume);
        });
        const dogImage = document.querySelector('.pet-promo-dog');

        if (dogImage) {
            dogImage.addEventListener('pointermove', (event) => {
                const rect = dogImage.getBoundingClientRect();
                const x = ((event.clientX - rect.left) / rect.width) - 0.5;
                const y = ((event.clientY - rect.top) / rect.height) - 0.5;

                dogImage.style.setProperty('--dog-shift-x', `${x * 10}px`);
                dogImage.style.setProperty('--dog-shift-y', `${y * 8}px`);
                dogImage.style.setProperty('--dog-scale', '1.015');
            });

            dogImage.addEventListener('pointerleave', () => {
                dogImage.style.setProperty('--dog-shift-x', '0px');
                dogImage.style.setProperty('--dog-shift-y', '0px');
                dogImage.style.setProperty('--dog-scale', '1');
            });
        }

        document.querySelectorAll('[data-top-picks-slider]').forEach((slider) => {
            const track = slider.querySelector('[data-top-picks-track]');
            const previous = slider.querySelector('[data-top-picks-prev]');
            const next = slider.querySelector('[data-top-picks-next]');

            if (!track || !previous || !next) {
                return;
            }

            const scrollByCard = (direction) => {
                const card = track.querySelector('.top-pick-card');
                const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
                const distance = card ? card.getBoundingClientRect().width + gap : track.clientWidth;

                track.scrollBy({
                    left: direction * distance,
                    behavior: 'smooth',
                });
            };

            previous.addEventListener('click', () => scrollByCard(-1));
            next.addEventListener('click', () => scrollByCard(1));

            let autoplayTimer;
            let autoplayPaused = false;
            const stopAutoplay = () => window.clearInterval(autoplayTimer);
            const startAutoplay = () => {
                stopAutoplay();
                autoplayTimer = window.setInterval(() => {
                    if (autoplayPaused || track.scrollWidth <= track.clientWidth + 2) {
                        return;
                    }

                    const atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 2;

                    if (atEnd) {
                        track.scrollTo({ left: 0, behavior: 'smooth' });
                        return;
                    }

                    scrollByCard(1);
                }, 3500);
            };

            slider.addEventListener('mouseenter', () => { autoplayPaused = true; });
            slider.addEventListener('mouseleave', () => { autoplayPaused = false; });
            slider.addEventListener('focusin', () => { autoplayPaused = true; });
            slider.addEventListener('focusout', () => { autoplayPaused = false; });
            startAutoplay();
        });

        document.querySelectorAll('[data-top-categories-slider]').forEach((slider) => {
            const track = slider.querySelector('[data-top-categories-track]');
            const previous = slider.querySelector('[data-top-categories-prev]');
            const next = slider.querySelector('[data-top-categories-next]');

            if (!track || !previous || !next) {
                return;
            }

            const updateControls = () => {
                const maxScroll = track.scrollWidth - track.clientWidth;
                previous.hidden = maxScroll <= 2 || track.scrollLeft <= 2;
                next.hidden = maxScroll <= 2 || track.scrollLeft >= maxScroll - 2;
            };
            const scrollByCard = (direction) => {
                const card = track.querySelector('.top-category-card');
                const gap = parseFloat(getComputedStyle(track).columnGap) || 0;
                track.scrollBy({ left: direction * (card ? card.getBoundingClientRect().width + gap : track.clientWidth), behavior: 'smooth' });
            };

            previous.addEventListener('click', () => scrollByCard(-1));
            next.addEventListener('click', () => scrollByCard(1));
            track.addEventListener('scroll', updateControls, { passive: true });
            window.addEventListener('resize', updateControls);
            updateControls();
        });
    });
</script>
@endpush
