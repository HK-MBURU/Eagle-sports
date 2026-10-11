import { useState, useRef, useEffect } from "react";
import { ChevronDown, ChevronRight, Search } from "lucide-react";
import { Button } from "@/components/ui/button";
import { navigationData, NavItem } from "@/data/navigationData";
import SearchBar from "@/components/SearchBar";
import { useSearchNavigation } from '@/hooks/useSearchNavigation';
import { SearchResult } from '@/types/search';

const Navigation = () => {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState<string | null>(null);
  const [activeMobileMenu, setActiveMobileMenu] = useState<string[]>([]);
  const [isScrolled, setIsScrolled] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);
  const dropdownTimeoutRef = useRef<NodeJS.Timeout>();

  const logo = '/images/logo1.png';
  const logo3 = '/images/logo3.png';

  // Close dropdown when clicking outside
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setActiveDropdown(null);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  // Handle scroll detection
  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 10);
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  // Lock body scroll and prevent overflow when mobile menu is open
  useEffect(() => {
    if (isMobileMenuOpen) {
      const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
      document.body.style.overflow = 'hidden';
      document.body.style.paddingRight = `${scrollbarWidth}px`;
    } else {
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
    }

    return () => {
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
    };
  }, [isMobileMenuOpen]);

  const toggleMobileSubmenu = (itemName: string) => {
    setActiveMobileMenu(prev =>
      prev.includes(itemName)
        ? prev.filter(name => name !== itemName)
        : [...prev, itemName]
    );
  };

  const closeMobileMenu = () => {
    setIsMobileMenuOpen(false);
    setActiveMobileMenu([]);
  };

  const { handleSearchResultClick } = useSearchNavigation();

  const handleSearchResultSelection = (result: SearchResult) => {
    handleSearchResultClick(result);
    closeMobileMenu();
  };

  // Improved dropdown handlers with delay
  const handleDropdownEnter = (itemName: string) => {
    if (dropdownTimeoutRef.current) {
      clearTimeout(dropdownTimeoutRef.current);
    }
    setActiveDropdown(itemName);
  };

  const handleDropdownLeave = () => {
    dropdownTimeoutRef.current = setTimeout(() => {
      setActiveDropdown(null);
    }, 150); // Small delay to allow moving to dropdown
  };

  const handleDropdownClick = (itemName: string) => {
    setActiveDropdown(activeDropdown === itemName ? null : itemName);
  };

  return (
    <>
      <nav
        className="fixed top-0 left-0 right-0 z-50 backdrop-blur-md border-b border-gray-200 transition-colors duration-300"
        style={{
          backgroundColor: isScrolled ? 'rgba(245, 244, 240, 0.98)' : 'rgba(245, 244, 240, 0.95)',
          transform: 'translateZ(0)',
          WebkitTransform: 'translateZ(0)'
        }}
      >
        <div className="w-full max-w-7xl mx-auto px-4 sm:px-6">
          <div className="flex items-center justify-between h-16 md:h-20">
            {/* Logo */}
            <a
              href="/"
              className="flex items-center gap-2 md:gap-3 px-2.5 py-1.5 md:px-3 md:py-2 rounded-lg transition-all duration-200 group flex-shrink-0"
              style={{ backgroundColor: '#0F3E22' }}
            >
              <img
                src={logo}
                alt="Logo"
                className="h-9 w-9 md:h-11 md:w-11 transition-transform group-hover:scale-105"
              />
              <div className="hidden sm:block">
                <img
                  src={logo3}
                  alt="Beyond the Finish Line"
                  className="h-9 md:h-11"
                />
              </div>
            </a>

            {/* Desktop Navigation */}
            <div
              className="hidden lg:flex items-center space-x-1"
              ref={dropdownRef}
              onMouseLeave={handleDropdownLeave}
            >
              {navigationData.map((item) => (
                <DesktopNavItem
                  key={item.name}
                  item={item}
                  activeDropdown={activeDropdown}
                  onMouseEnter={handleDropdownEnter}
                  onMouseLeave={handleDropdownLeave}
                  onClick={handleDropdownClick}
                />
              ))}
            </div>

            {/* Desktop Search Bar - Always Visible */}
            <div className="hidden lg:flex items-center flex-1 max-w-md ml-8">
              <SearchBar
                onResultClick={handleSearchResultSelection}
                placeholder="Search stories, athletes, events..."
                variant="desktop"
              />
            </div>

            {/* Mobile Search and Menu Button */}
            <div className="flex lg:hidden items-center space-x-3">
              {/* Mobile Search - Integrated in Navigation */}
              <div className="flex-1 max-w-xs">
                <SearchBar
                  onResultClick={handleSearchResultSelection}
                  placeholder="Search..."
                  variant="mobile"
                />
              </div>

              {/* Modern Mobile Menu Button */}
              <button
                onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
                className="relative w-10 h-10 flex items-center justify-center rounded-lg transition-all duration-200 focus:outline-none"
                style={{
                  backgroundColor: isMobileMenuOpen ? 'rgba(0, 0, 0, 0.05)' : 'transparent'
                }}
                aria-label="Toggle menu"
                aria-expanded={isMobileMenuOpen}
              >
                <div className="w-5 h-4 flex flex-col justify-between">
                  <span
                    className={`block h-0.5 w-full bg-gray-700 rounded-full transition-all duration-300 ${isMobileMenuOpen ? 'rotate-45 translate-y-[7px]' : ''
                      }`}
                  />
                  <span
                    className={`block h-0.5 w-full bg-gray-700 rounded-full transition-all duration-200 ${isMobileMenuOpen ? 'opacity-0 scale-0' : 'opacity-100 scale-100'
                      }`}
                  />
                  <span
                    className={`block h-0.5 w-full bg-gray-700 rounded-full transition-all duration-300 ${isMobileMenuOpen ? '-rotate-45 -translate-y-[7px]' : ''
                      }`}
                  />
                </div>
              </button>
            </div>
          </div>
        </div>
      </nav>

      {/* Mobile Navigation Overlay */}
      <div
        className={`fixed inset-0 z-40 lg:hidden transition-all duration-300 ${isMobileMenuOpen ? 'visible' : 'invisible'
          }`}
      >
        {/* Backdrop */}
        <div
          className={`absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 ${isMobileMenuOpen ? 'opacity-100' : 'opacity-0'
            }`}
          onClick={closeMobileMenu}
        />

        {/* Mobile Menu Panel - Slides from Right */}
        <div
          className={`fixed top-16 md:top-20 right-0 bottom-0 w-full sm:w-80 bg-white shadow-2xl transform transition-transform duration-300 ease-out ${isMobileMenuOpen ? 'translate-x-0' : 'translate-x-full'
            }`}
          style={{
            transform: isMobileMenuOpen ? 'translateX(0) translateZ(0)' : 'translateX(100%) translateZ(0)',
            WebkitTransform: isMobileMenuOpen ? 'translateX(0) translateZ(0)' : 'translateX(100%) translateZ(0)'
          }}
        >
          <div className="h-full flex flex-col">
            {/* Search Bar in Mobile Menu */}
            <div className="px-4 py-4 border-b border-gray-100">
              <SearchBar
                onResultClick={(result) => {
                  handleSearchResultSelection(result);
                  closeMobileMenu();
                }}
                placeholder="Search stories, athletes..."
                variant="mobile-menu"
              />
            </div>

            {/* Scrollable Menu Items */}
            <div className="flex-1 overflow-y-auto overscroll-contain">
              <div className="px-4 py-4 space-y-1">
                {navigationData.map((item) => (
                  <MobileNavItem
                    key={item.name}
                    item={item}
                    activeMobileMenu={activeMobileMenu}
                    toggleMobileSubmenu={toggleMobileSubmenu}
                    closeMobileMenu={closeMobileMenu}
                    level={0}
                  />
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};


// Desktop Navigation Item Component
interface DesktopNavItemProps {
  item: NavItem;
  activeDropdown: string | null;
  onMouseEnter: (name: string) => void;
  onMouseLeave: () => void;
  onClick: (name: string) => void;
}

const DesktopNavItem = ({ item, activeDropdown, onMouseEnter, onMouseLeave, onClick }: DesktopNavItemProps) => {
  const hasChildren = item.children && item.children.length > 0;
  const isActive = activeDropdown === item.name;
  const buttonRef = useRef<HTMLButtonElement>(null);
  const [dropdownPosition, setDropdownPosition] = useState<'left' | 'right'>('left');

  useEffect(() => {
    if (isActive && buttonRef.current) {
      const rect = buttonRef.current.getBoundingClientRect();
      const viewportWidth = window.innerWidth;
      const dropdownWidth = 256;

      if (rect.left + dropdownWidth > viewportWidth - 20) {
        setDropdownPosition('right');
      } else {
        setDropdownPosition('left');
      }
    }
  }, [isActive]);

  if (!hasChildren) {
    return (
      <a
        href={item.href || "#"}
        className="block px-4 py-2 text-gray-700 hover:text-accent-orange font-medium rounded-lg hover:bg-gray-50/80 transition-all duration-200 text-[15px]"
      >
        {item.name}
      </a>
    );
  }

  return (
    <div className="relative">
      <button
        ref={buttonRef}
        className="flex items-center gap-1.5 px-4 py-2 text-gray-700 hover:text-accent-orange font-medium rounded-lg hover:bg-gray-50/80 transition-all duration-200 text-[15px]"
        onMouseEnter={() => onMouseEnter(item.name)}
        onMouseLeave={onMouseLeave}
        onClick={() => onClick(item.name)}
      >
        {item.name}
        <ChevronDown
          size={16}
          className={`transition-transform duration-200 ${isActive ? 'rotate-180' : ''}`}
        />
      </button>

      {/* Dropdown Menu */}
      {isActive && (
        <div
          className={`absolute top-full mt-1 w-64 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 ${dropdownPosition === 'right' ? 'right-0' : 'left-0'
            }`}
          onMouseEnter={() => onMouseEnter(item.name)}
          onMouseLeave={onMouseLeave}
        >
          {item.children?.map((child) => (
            <DropdownItem
              key={child.name}
              item={child}
              onClose={() => onClick(item.name)}
            />
          ))}
        </div>
      )}
    </div>
  );
};

// Dropdown Item Component
interface DropdownItemProps {
  item: NavItem;
  onClose: () => void;
}

const DropdownItem = ({ item, onClose }: DropdownItemProps) => {
  const [isHovered, setIsHovered] = useState(false);
  const hasChildren = item.children && item.children.length > 0;
  const itemRef = useRef<HTMLDivElement>(null);
  const [nestedPosition, setNestedPosition] = useState<'right' | 'left'>('right');
  const nestedTimeoutRef = useRef<NodeJS.Timeout>();

  useEffect(() => {
    if (isHovered && itemRef.current && hasChildren) {
      const rect = itemRef.current.getBoundingClientRect();
      const viewportWidth = window.innerWidth;
      const nestedWidth = 224;

      if (rect.right + nestedWidth > viewportWidth - 20) {
        setNestedPosition('left');
      } else {
        setNestedPosition('right');
      }
    }
  }, [isHovered, hasChildren]);

  const handleMouseEnter = () => {
    if (nestedTimeoutRef.current) {
      clearTimeout(nestedTimeoutRef.current);
    }
    setIsHovered(true);
  };

  const handleMouseLeave = () => {
    nestedTimeoutRef.current = setTimeout(() => {
      setIsHovered(false);
    }, 150);
  };

  if (!hasChildren) {
    return (
      <a
        href={item.href || "#"}
        className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-accent-orange/5 hover:text-accent-orange transition-colors font-medium"
        onClick={onClose}
      >
        {item.name}
      </a>
    );
  }

  return (
    <div
      ref={itemRef}
      className="relative"
      onMouseEnter={handleMouseEnter}
      onMouseLeave={handleMouseLeave}
    >
      <button className="w-full flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-accent-orange/5 hover:text-accent-orange transition-colors font-medium">
        <span>{item.name}</span>
        <ChevronRight size={14} className={nestedPosition === 'left' ? 'rotate-180' : ''} />
      </button>

      {isHovered && (
        <div
          className={`absolute top-0 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 ${nestedPosition === 'right' ? 'left-full ml-1' : 'right-full mr-1'
            }`}
          onMouseEnter={handleMouseEnter}
          onMouseLeave={handleMouseLeave}
        >
          {item.children?.map((subChild) => (
            <a
              key={subChild.name}
              href={subChild.href || "#"}
              className="block px-4 py-2.5 text-sm text-gray-700 hover:bg-accent-orange/5 hover:text-accent-orange transition-colors"
              onClick={onClose}
            >
              {subChild.name}
            </a>
          ))}
        </div>
      )}
    </div>
  );
};

// Mobile Navigation Item Component
interface MobileNavItemProps {
  item: NavItem;
  activeMobileMenu: string[];
  toggleMobileSubmenu: (name: string) => void;
  closeMobileMenu: () => void;
  level: number;
}

const MobileNavItem = ({
  item,
  activeMobileMenu,
  toggleMobileSubmenu,
  closeMobileMenu,
  level
}: MobileNavItemProps) => {
  const hasChildren = item.children && item.children.length > 0;
  const isActive = activeMobileMenu.includes(item.name);
  const paddingLeft = level * 12 + 12;

  if (!hasChildren) {
    return (
      <a
        href={item.href || "#"}
        onClick={closeMobileMenu}
        className="block py-3 px-3 text-gray-700 hover:text-accent-orange hover:bg-accent-orange/5 rounded-lg transition-all duration-150 font-medium text-[15px]"
        style={{ paddingLeft: `${paddingLeft}px` }}
      >
        {item.name}
      </a>
    );
  }

  return (
    <div className="overflow-hidden">
      <button
        onClick={() => toggleMobileSubmenu(item.name)}
        className="w-full flex items-center justify-between py-3 px-3 text-gray-700 hover:text-accent-orange hover:bg-accent-orange/5 rounded-lg transition-all duration-150 font-medium text-[15px]"
        style={{ paddingLeft: `${paddingLeft}px` }}
      >
        <span>{item.name}
        </span>
        <ChevronDown
          size={18}
          className={`flex-shrink-0 transition-transform duration-300 ${isActive ? 'rotate-180 text-accent-orange' : 'text-gray-400'
            }`}
        />
      </button>

      {/* Submenu with smooth animation */}
      <div
        className={`transition-all duration-300 ease-in-out ${isActive ? 'max-h-[1000px] opacity-100 mt-0.5' : 'max-h-0 opacity-0'
          }`}
      >
        <div className="space-y-0.5">
          {item.children?.map((child) => (
            <MobileNavItem
              key={child.name}
              item={child}
              activeMobileMenu={activeMobileMenu}
              toggleMobileSubmenu={toggleMobileSubmenu}
              closeMobileMenu={closeMobileMenu}
              level={level + 1}
            />
          ))}
        </div>
      </div>
    </div>
  );
};

export default Navigation;