document.addEventListener("DOMContentLoaded", () => {
    
    // --------------------------------------------------------------------------------
    // LOGICA DROPDOWN GENERALĂ (Domenii & Suport)
    // --------------------------------------------------------------------------------
    
    /**
     * Setează funcționalitatea de dropdown pentru un buton și un meniu specific.
     * @param {string} buttonId - ID-ul butonului.
     * @param {string} menuId - ID-ul meniului.
     */
    function setupDropdown(buttonId, menuId) {
        const button = document.getElementById(buttonId);
        const menu = document.getElementById(menuId);

        if (button && menu) {
            // Asigură-te că meniul începe invizibil (dacă îi lipsesc clasele din HTML)
            if (!menu.classList.contains('invisible')) {
                menu.classList.add('invisible', 'opacity-0');
            }
            
            const toggleDropdown = (isOpening) => {
                if (isOpening) {
                    // Închide toate celelalte dropdown-uri înainte de a-l deschide pe acesta
                    closeAllDropdowns(buttonId);
                    
                    menu.classList.remove('invisible', 'opacity-0');
                    menu.classList.add('visible', 'opacity-100');
                    button.setAttribute('aria-expanded', 'true');
                } else {
                    menu.classList.remove('visible', 'opacity-100');
                    menu.classList.add('invisible', 'opacity-0');
                    button.setAttribute('aria-expanded', 'false');
                }
            };
            
            // Logica pentru închiderea celorlalte meniuri
            function closeAllDropdowns(excludeButtonId) {
                 // Listează ID-urile tuturor butoanelor/meniurilor dropdown pe care le gestionezi
                const dropdowns = [
                    { button: 'domainDropdownButton', menu: 'domainDropdownMenu' },
                    { button: 'supportDropdownButton', menu: 'supportDropdownMenu' }
                ];
                
                dropdowns.forEach(({ button: otherButtonId, menu: otherMenuId }) => {
                    if (otherButtonId !== excludeButtonId) {
                        const otherMenu = document.getElementById(otherMenuId);
                        const otherButton = document.getElementById(otherButtonId);
                        if (otherMenu && otherMenu.classList.contains('visible')) {
                            otherMenu.classList.remove('visible', 'opacity-100');
                            otherMenu.classList.add('invisible', 'opacity-0');
                            if (otherButton) otherButton.setAttribute('aria-expanded', 'false');
                        }
                    }
                });
            }

            button.addEventListener('click', (event) => {
                event.preventDefault(); 
                event.stopPropagation(); 
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                toggleDropdown(!isExpanded);
            });

            document.addEventListener('click', (event) => {
                if (menu.classList.contains('visible') && !menu.contains(event.target) && !button.contains(event.target)) {
                    toggleDropdown(false);
                }
            });
        }
    }
    
    // Inițializarea dropdown-urilor
    setupDropdown('domainDropdownButton', 'domainDropdownMenu');
    setupDropdown('supportDropdownButton', 'supportDropdownMenu');


    // --------------------------------------------------------------------------------
    // LOGICA PAGINII DE GĂZDUIRE (Pricing Cards, TVA, Switch SSD/NVMe)
    // --------------------------------------------------------------------------------
    
    const TVA_RATE = 0.21;
    const ssdOption = document.getElementById('ssd-option');
    const nvmeOption = document.getElementById('nvme-option');
    const cardsContainer = document.getElementById('pricing-cards-container');
    const tvaSwitch = document.getElementById('tva-switch');
    const tvaHandle = document.getElementById('tva-switch-handle');
    const tvaOnLabel = document.getElementById('tva-on-label');
    const tvaOffLabel = document.getElementById('tva-off-label');

    if (cardsContainer && ssdOption && nvmeOption) {
        let currentHostingType = 'ssd';
        let isTvaOn = false; 

        const PRICING_PLANS = {
            'ssd': [
                { 
                    id: 'SSD-START', 
                    name: 'SSD START', 
                    recommended: false, 
                    discountTag: '-44% DISCOUNT', 
                    discountText: 'Reducere -44% la plata pe 6 sau 12 luni cu codul SNTR31', 
                    priceNoTVA: 2.79, 
                    oldPriceNoTVA: 4.99, 
                    description: 'Pachet de găzduire SSD rapidă, ideal pentru site-uri mici și startup-uri aflate la început de drum.', 
                    specs: [
                        { label: 'Stocare', value: '25GB SSD' }, 
                        { label: 'Memorie RAM', value: '1.5GB DDR4 ECC' }, 
                        { label: 'Nivel Procesor', value: '150% vCPU' }, 
                        { label: 'Domenii', value: 'MAX 3 DOMENII' }
                    ], 
                    features: [
                        'Căsuțe email: Creezi câte vrei', 
                        'Compatibil WordPress', 
                        'Optimizat pentru performanță maximă', 
                        'Backup zilnic în 2X locații', 
                        'Cu retenție până la 3 luni', 
                        'Domeniu .RO Gratuit (Gratuit la plata anuală)', 
                        'Anti-Malware by Imunify360', 
                        'cPanel + CloudLinux v8', 
                        'Softaculous App Installer', 
                        'SSL Let\'s Encrypt inclus'
                    ]
                },
                { 
                    id: 'SSD-PLUS', 
                    name: 'SSD PLUS', 
                    recommended: true, 
                    discountTag: '-44% DISCOUNT', 
                    discountText: 'Reducere -44% la plata pe 6 sau 12 luni cu codul SNTR31', 
                    priceNoTVA: 4.47, 
                    oldPriceNoTVA: 7.99, 
                    description: 'Soluția ideală pentru bloguri, magazine online mici și site-uri cu trafic mediu.', 
                    specs: [
                        { label: 'Stocare', value: '50GB SSD' }, 
                        { label: 'Memorie RAM', value: '3GB DDR4 ECC' }, 
                        { label: 'Nivel Procesor', value: '300% vCPU' }, 
                        { label: 'Domenii', value: 'MAX 10 DOMENII' }
                    ], 
                    features: [
                        'Căsuțe email: Creezi câte vrei', 
                        'Compatibil WordPress', 
                        'Optimizat pentru performanță maximă', 
                        'Backup zilnic în 2X locații', 
                        'Cu retenție până la 3 luni', 
                        'Domeniu .RO Gratuit (Gratuit la plata anuală)', 
                        'Anti-Malware by Imunify360', 
                        'cPanel + CloudLinux v8', 
                        'Softaculous App Installer', 
                        'SSL Let\'s Encrypt inclus'
                    ]
                },
                { 
                    id: 'SSD-PRO', 
                    name: 'SSD PRO', 
                    recommended: false, 
                    discountTag: '-44% DISCOUNT', 
                    discountText: 'Reducere -44% la plata pe 6 sau 12 luni cu codul SNTR31', 
                    priceNoTVA: 8.39, 
                    oldPriceNoTVA: 14.99, 
                    description: 'Pachet puternic pentru site-uri de business, portaluri și proiecte mari cu cerințe de resurse sporite.', 
                    specs: [
                        { label: 'Stocare', value: '100GB SSD' }, 
                        { label: 'Memorie RAM', value: '6GB DDR4 ECC' }, 
                        { label: 'Nivel Procesor', value: '600% vCPU' }, 
                        { label: 'Domenii', value: 'NELIMITAT' }
                    ], 
                    features: [
                        'Căsuțe email: Creezi câte vrei', 
                        'Compatibil WordPress', 
                        'Optimizat pentru performanță maximă', 
                        'Backup zilnic în 2X locații', 
                        'Cu retenție până la 3 luni', 
                        'Domeniu .RO Gratuit (Gratuit la plata anuală)', 
                        'Anti-Malware by Imunify360', 
                        'cPanel + CloudLinux v8', 
                        'Softaculous App Installer', 
                        'SSL Let\'s Encrypt inclus'
                    ]
                },
                { 
                    id: 'SSD-MAX', 
                    name: 'SSD MAX', 
                    recommended: false, 
                    discountTag: '-44% DISCOUNT', 
                    discountText: 'Reducere -44% la plata pe 6 sau 12 luni cu codul SNTR31', 
                    priceNoTVA: 16.79, 
                    oldPriceNoTVA: 29.99, 
                    description: 'Pachet de elită, dedicat site-urilor cu trafic foarte mare și cerințe maxime de viteză și stabilitate.', 
                    specs: [
                        { label: 'Stocare', value: '200GB SSD' }, 
                        { label: 'Memorie RAM', value: '12GB DDR4 ECC' }, 
                        { label: 'Nivel Procesor', value: '1200% vCPU' }, 
                        { label: 'Domenii', value: 'NELIMITAT' }
                    ], 
                    features: [
                        'Căsuțe email: Creezi câte vrei', 
                        'Compatibil WordPress', 
                        'Optimizat pentru performanță maximă', 
                        'Backup zilnic în 2X locații', 
                        'Cu retenție până la 3 luni', 
                        'Domeniu .RO Gratuit (Gratuit la plata anuală)', 
                        'Anti-Malware by Imunify360', 
                        'cPanel + CloudLinux v8', 
                        'Softaculous App Installer', 
                        'SSL Let\'s Encrypt inclus'
                    ]
                }
            ],
            'nvme': [
                { 
                    id: 'NVMe-START', 
                    name: 'NVMe START', 
                    recommended: false, 
                    discountTag: '-44% DISCOUNT', 
                    discountText: 'Reducere -44% la plata pe 6 sau 12 luni cu codul SNTR31', 
                    priceNoTVA: 3.35, 
                    oldPriceNoTVA: 5.99, 
                    description: 'Pachet de găzduire NVMe ultra-rapidă, ideal pentru viteze maxime la început de drum.', 
                    specs: [
                        { label: 'Stocare', value: '25GB NVMe' }, 
                        { label: 'Memorie RAM', value: '1.5GB DDR4 ECC' }, 
                        { label: 'Nivel Procesor', value: '200% vCPU' }, 
                        { label: 'Domenii', value: 'MAX 3 DOMENII' }
                    ], 
                    features: [
                        'Căsuțe email: Creezi câte vrei', 
                        'Compatibil WordPress', 
                        'Optimizat pentru performanță maximă', 
                        'Backup zilnic în 2X locații', 
                        'Cu retenție până la 3 luni', 
                        'Domeniu .RO Gratuit (Gratuit la plata anuală)', 
                        'Anti-Malware by Imunify360', 
                        'cPanel + CloudLinux v8', 
                        'Softaculous App Installer', 
                        'SSL Let\'s Encrypt inclus'
                    ]
                },
                { 
                    id: 'NVMe-PLUS', 
                    name: 'NVMe PLUS', 
                    recommended: true, 
                    discountTag: '-44% DISCOUNT', 
                    discountText: 'Reducere -44% la plata pe 6 sau 12 luni cu codul SNTR31', 
                    priceNoTVA: 5.59, 
                    oldPriceNoTVA: 9.99, 
                    description: 'Soluția de top pentru magazine online performante și site-uri cu trafic mare care necesită cea mai rapidă stocare.', 
                    specs: [
                        { label: 'Stocare', value: '50GB NVMe' }, 
                        { label: 'Memorie RAM', value: '3GB DDR4 ECC' }, 
                        { label: 'Nivel Procesor', value: '400% vCPU' }, 
                        { label: 'Domenii', value: 'MAX 10 DOMENII' }
                    ], 
                    features: [
                        'Căsuțe email: Creezi câte vrei', 
                        'Compatibil WordPress', 
                        'Optimizat pentru performanță maximă', 
                        'Backup zilnic în 2X locații', 
                        'Cu retenție până la 3 luni', 
                        'Domeniu .RO Gratuit (Gratuit la plata anuală)', 
                        'Anti-Malware by Imunify360', 
                        'cPanel + CloudLinux v8', 
                        'Softaculous App Installer', 
                        'SSL Let\'s Encrypt inclus'
                    ]
                },
                { 
                    id: 'NVMe-PRO', 
                    name: 'NVMe PRO', 
                    recommended: false, 
                    discountTag: '-44% DISCOUNT', 
                    discountText: 'Reducere -44% la plata pe 6 sau 12 luni cu codul SNTR31', 
                    priceNoTVA: 11.19, 
                    oldPriceNoTVA: 19.99, 
                    description: 'Server dedicat virtual, ideal pentru aplicații web complexe și baze de date intensive.', 
                    specs: [
                        { label: 'Stocare', value: '100GB NVMe' }, 
                        { label: 'Memorie RAM', value: '6GB DDR4 ECC' }, 
                        { label: 'Nivel Procesor', value: '800% vCPU' }, 
                        { label: 'Domenii', value: 'NELIMITAT' }
                    ], 
                    features: [
                        'Căsuțe email: Creezi câte vrei', 
                        'Compatibil WordPress', 
                        'Optimizat pentru performanță maximă', 
                        'Backup zilnic în 2X locații', 
                        'Cu retenție până la 3 luni', 
                        'Domeniu .RO Gratuit (Gratuit la plata anuală)', 
                        'Anti-Malware by Imunify360', 
                        'cPanel + CloudLinux v8', 
                        'Softaculous App Installer', 
                        'SSL Let\'s Encrypt inclus'
                    ]
                },
                { 
                    id: 'NVMe-MAX', 
                    name: 'NVMe MAX', 
                    recommended: false, 
                    discountTag: '-44% DISCOUNT', 
                    discountText: 'Reducere -44% la plata pe 6 sau 12 luni cu codul SNTR31', 
                    priceNoTVA: 22.39, 
                    oldPriceNoTVA: 39.99, 
                    description: 'Putere maximă și izolare completă. Pentru cele mai exigente proiecte.', 
                    specs: [
                        { label: 'Stocare', value: '200GB NVMe' }, 
                        { label: 'Memorie RAM', value: '12GB DDR4 ECC' }, 
                        { label: 'Nivel Procesor', value: '1600% vCPU' }, 
                        { label: 'Domenii', value: 'NELIMITAT' }
                    ], 
                    features: [
                        'Căsuțe email: Creezi câte vrei', 
                        'Compatibil WordPress', 
                        'Optimizat pentru performanță maximă', 
                        'Backup zilnic în 2X locații', 
                        'Cu retenție până la 3 luni', 
                        'Domeniu .RO Gratuit (Gratuit la plata anuală)', 
                        'Anti-Malware by Imunify360', 
                        'cPanel + CloudLinux v8', 
                        'Softaculous App Installer', 
                        'SSL Let\'s Encrypt inclus'
                    ]
                }
            ],
        };

        function formatPrice(priceNoTVA) {
            const price = isTvaOn ? priceNoTVA * (1 + TVA_RATE) : priceNoTVA;
            return price.toFixed(2);
        }

        function getFeatureIcon(featureText) {
            featureText = featureText.toLowerCase();
            if (featureText.includes('email')) return 'mail';
            if (featureText.includes('wordpress')) return 'package-check';
            if (featureText.includes('performanță')) return 'zap';
            if (featureText.includes('backup')) return 'cloud-drizzle';
            if (featureText.includes('anti-malware') || featureText.includes('imunify360')) return 'shield-check';
            if (featureText.includes('cpanel') || featureText.includes('cloudlinux')) return 'server-cog';
            if (featureText.includes('softaculous')) return 'box';
            if (featureText.includes('ssl')) return 'lock';
            if (featureText.includes('domeniu')) return 'globe';
            return 'check-circle';
        }

        function renderPricingCard(plan, hostingType) {
            const isRecommended = plan.recommended;
            const cardClasses = isRecommended
                ? 'bg-plus-recommended border-2 border-sentra-cyan shadow-xl scale-[1.02] transition duration-300'
                : 'bg-white border-2 border-gray-100 transition duration-300 hover:shadow-lg';
            const buttonClasses = isRecommended
                ? 'btn-primary w-full py-3 rounded-lg font-bold text-lg mt-6 flex items-center justify-center'
                : 'bg-gray-200 text-gray-800 w-full py-3 rounded-lg font-bold text-lg mt-6 hover:bg-gray-300 transition duration-300 flex items-center justify-center';
            
            const recommendedTag = isRecommended
                ? `
                <div class="absolute top-0 right-0 mt-[-1rem] mr-[-1rem] transform rotate-12">
                    <span class="inline-flex items-center px-4 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full shadow-lg plus-tag-outline">
                        <i data-lucide="star" class="w-4 h-4 mr-1 fill-sentra-cyan"></i>
                        Cel mai apreciat
                    </span>
                </div>
                `
                : '';

            const specsHtml = plan.specs.map(spec => `
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-700 font-semibold">${spec.label}</span>
                    <span class="text-sm font-bold text-[#1A1A1A]">${spec.value}</span>
                </div>
            `).join('');

            const featuresHtml = plan.features.map(feature => {
                const iconName = getFeatureIcon(feature);
                return `
                    <li class="flex items-start text-sm text-gray-700 mb-2">
                        <i data-lucide="${iconName}" class="w-5 h-5 mr-2 feature-check flex-shrink-0"></i>
                        <span>${feature.replace(/\((.*?)\)/g, '<span class="text-xs text-gray-500 font-normal ml-1">($1)</span>').replace(/\*([^*]+)\*/g, '<strong>$1</strong>')}</span>
                    </li>
                `;
            }).join('');

            return `
                <div class="swiper-slide p-6 flex flex-col rounded-2xl card-shadow ${cardClasses} relative">
                    ${recommendedTag}
                    
                    <div class="mb-4">
                        <h3 class="text-2xl font-extrabold text-[#1A1A1A] mb-1">${plan.name}</h3>
                        <span class="text-xs font-bold uppercase tracking-wider text-white px-2 py-0.5 rounded-md discount-label">${plan.discountTag}</span>
                    </div>

                    <p class="text-sm text-gray-600 mb-4 font-medium">${plan.discountText}</p>

                    <div class="flex items-end mb-6">
                        <p class="text-xl font-medium text-gray-400 line-through mr-3">${formatPrice(plan.oldPriceNoTVA)}€</p>
                        <p class="text-4xl font-extrabold text-sentra-cyan leading-none">${formatPrice(plan.priceNoTVA)}€</p>
                        <p class="text-base text-[#1A1A1A] font-semibold ml-1">lună</p>
                    </div>

                    <p class="text-gray-600 text-base mb-6">${plan.description}</p>

                    <div class="border-t border-b border-gray-100 py-4 mb-6">
                        ${specsHtml}
                    </div>

                    <ul class="flex-grow space-y-3">
                        ${featuresHtml}
                    </ul>

                    <a href="pages/checkout.php?plan_id=${plan.id}&plan_name=${encodeURIComponent(plan.name)}&amount=${plan.priceNoTVA}&cycle=lunar&type=shared" 
   class="${buttonClasses}">
    Comandă Acum
    <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
</a>
                </div>
            `;
        }

        function renderPricingCards(hostingType) {
            const plans = PRICING_PLANS[hostingType];
            cardsContainer.innerHTML = plans.map(plan => renderPricingCard(plan, hostingType)).join('');
            
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        }

        function updateHostingSwitchStyle(type) {
            const existingDot = nvmeOption.querySelector('.nvme-dot');
            if (existingDot) existingDot.remove();
            
            [ssdOption, nvmeOption].forEach(el => {
                el.className = 'py-3 px-6 rounded-full text-center cursor-pointer text-gray-700 hover:text-[#1A1A1A] transition duration-300 relative';
                el.classList.remove('pl-10');
            });

            if (type === 'ssd') {
                ssdOption.classList.add('bg-active-nvme', 'shadow-inner', 'font-semibold', 'text-[#1A1A1A]');
                ssdOption.innerHTML = 'Găzduire SSD';
                nvmeOption.innerHTML = 'Găzduire NVMe';
            } else {
                nvmeOption.classList.add('bg-active-nvme', 'shadow-inner', 'font-semibold', 'text-[#1A1A1A]');
                nvmeOption.innerHTML = 'Găzduire NVMe';
                ssdOption.innerHTML = 'Găzduire SSD';

                const dotHtml = document.createElement('div');
                dotHtml.className = 'nvme-dot absolute left-3 top-1/2 transform -translate-y-1/2';
                nvmeOption.prepend(dotHtml);
                nvmeOption.classList.add('pl-10');
            }
        }
        
        function updateTvaSwitchStyle() {
            if (isTvaOn) {
                tvaHandle.classList.add("translate-x-6");
                tvaOnLabel.classList.remove("text-gray-500", "font-semibold");
                tvaOnLabel.classList.add("text-[#1A1A1A]", "font-bold");
                tvaOffLabel.classList.remove("text-[#1A1A1A]", "font-bold");
                tvaOffLabel.classList.add("text-gray-500", "font-semibold");
            } else {
                tvaHandle.classList.remove("translate-x-6");
                tvaOffLabel.classList.remove("text-gray-500", "font-semibold");
                tvaOffLabel.classList.add("text-[#1A1A1A]", "font-bold");
                tvaOnLabel.classList.remove("text-[#1A1A1A]", "font-bold");
                tvaOnLabel.classList.add("text-gray-500", "font-semibold");
            }
        }
        
        function toggleTva() {
            isTvaOn = !isTvaOn;
            updateTvaSwitchStyle();
            renderPricingCards(currentHostingType);
        }
        
        function initSwiper() {
            if (typeof Swiper !== 'undefined') {
                const swiper = new Swiper(".mySwiper", {
                    slidesPerView: 1, 
                    spaceBetween: 24, 
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2, 
                            spaceBetween: 32,
                        },
                        1024: {
                            slidesPerView: 3, 
                            spaceBetween: 32,
                        },
                    },
                });
            }
        }
        
        ssdOption.addEventListener('click', () => {
            currentHostingType = 'ssd';
            updateHostingSwitchStyle('ssd');
            renderPricingCards('ssd');
        });

        nvmeOption.addEventListener('click', () => {
            currentHostingType = 'nvme';
            updateHostingSwitchStyle('nvme');
            renderPricingCards('nvme');
        });

        tvaSwitch.addEventListener('click', toggleTva);
        tvaOffLabel.addEventListener('click', () => {
            if(isTvaOn) toggleTva();
        });
        tvaOnLabel.addEventListener('click', () => {
            if(!isTvaOn) toggleTva();
        });

        updateHostingSwitchStyle(currentHostingType); 
        updateTvaSwitchStyle();
        renderPricingCards(currentHostingType);
        initSwiper(); 
    }

    // --------------------------------------------------------------------------------
    // LOGICA FAQ (Întrebări Frecvente)
    // --------------------------------------------------------------------------------
    function setupFaqToggle() {
        document.querySelectorAll('.faq-item').forEach(item => {
            const questionButton = item.querySelector('.faq-question');
            const answerDiv = item.querySelector('.faq-answer');
            const icon = item.querySelector('.faq-icon');

            if (!questionButton || !answerDiv || !icon) return;

            questionButton.addEventListener('click', () => {
                const isExpanded = answerDiv.classList.contains('active');

                document.querySelectorAll('.faq-item').forEach(otherItem => {
                    const otherAnswer = otherItem.querySelector('.faq-answer');
                    const otherIcon = otherItem.querySelector('.faq-icon');
                    
                    if (otherAnswer) {
                        otherAnswer.classList.remove('active');
                        otherAnswer.style.maxHeight = null;
                    }
                    if (otherIcon) {
                        otherIcon.setAttribute('data-lucide', 'plus');
                    }
                });
                
                if (!isExpanded) {
                    answerDiv.classList.add('active');
                    answerDiv.style.maxHeight = answerDiv.scrollHeight + "px";
                    icon.setAttribute('data-lucide', 'minus');
                } else {
                    answerDiv.classList.remove('active');
                    answerDiv.style.maxHeight = null;
                    icon.setAttribute('data-lucide', 'plus');
                }
                
                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    lucide.createIcons();
                }
            });
        });
    }

    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }

    setupFaqToggle();
});