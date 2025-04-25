# MyMythicalID
# MyMythicalID Build System
# ==========================================

# Directory configurations
FRONTEND_DIR = frontend
BACKEND_DIR = backend

# Commands
YARN = yarn
NPM = npm
PHP = php
COMPOSER = composer

# Colors and formatting
RED = \033[0;31m
GREEN = \033[0;32m
YELLOW = \033[1;33m
BLUE = \033[0;34m
PURPLE = \033[0;35m
CYAN = \033[0;36m
WHITE = \033[1;37m
BOLD = \033[1m
NC = \033[0m

# Emoji indicators
CHECK = ✓
WARN = ⚠
INFO = ℹ
ROCKET = 🚀
CLEAN = 🧹
PACKAGE = 📦
BUILD = 🔨
SERVER = 🌐

# Make sure we use bash
SHELL := /bin/bash

.PHONY: help frontend backend dev release install clean test

# Default target
help:
	@echo -e "${BOLD}${BLUE}MyMythicalID Build System${NC}"
	@echo -e "${CYAN}================================${NC}\n"
	@echo -e "${BOLD}Available commands:${NC}"
	@echo -e "  ${GREEN}make frontend${NC}    ${ROCKET} Builds the frontend for production"
	@echo -e "  ${GREEN}make backend${NC}     ${BUILD} Builds the backend components"
	@echo -e "  ${GREEN}make release${NC}     ${PACKAGE} Prepares a full release build"
	@echo -e "  ${GREEN}make install${NC}     ${INFO} Installs all dependencies"
	@echo -e "  ${GREEN}make clean${NC}       ${CLEAN} Cleans all build artifacts"
	@echo -e "  ${GREEN}make test${NC}        ${CHECK} Runs all tests\n"
	@echo -e "${YELLOW}Use 'make <command>' to execute a command${NC}\n"

# Frontend tasks
frontend:
	@echo -e "\n${BOLD}${BLUE}Frontend Build${NC} ${ROCKET}"
	@echo -e "${CYAN}=================${NC}"
	@echo -e "${GREEN}${INFO} Building frontend for production...${NC}"
	@cd $(FRONTEND_DIR) && $(YARN) build
	@echo -e "${GREEN}${CHECK} Frontend build complete!${NC}\n"

# Backend tasks
backend:
	@echo -e "\n${BOLD}${BLUE}Backend Build${NC} ${BUILD}"
	@echo -e "${CYAN}=================${NC}"
	@echo -e "${GREEN}${INFO} Building backend components...${NC}"
	@cd $(BACKEND_DIR) && $(COMPOSER) install
	@cd $(BACKEND_DIR) && $(COMPOSER) dump-autoload
	@echo -e "${GREEN}${CHECK} Backend build complete!${NC}\n"


# Release build
release:
	@echo -e "\n${BOLD}${BLUE}Release Build${NC} ${ROCKET}"
	@echo -e "${CYAN}=================${NC}"
	@echo -e "${YELLOW}${WARN} Starting comprehensive release build...${NC}\n"
	
	@echo -e "${PURPLE}${INFO} Frontend checks...${NC}"
	@cd $(FRONTEND_DIR) && $(YARN) lint
	@cd $(BACKEND_DIR) && $(COMPOSER) run lint
	@cd $(FRONTEND_DIR) && $(YARN) format
	@echo -e "${GREEN}${CHECK} Frontend checks complete${NC}\n"
	
	@echo -e "${PURPLE}${INFO} Updating dependencies...${NC}"
	@cd $(FRONTEND_DIR) && $(YARN) upgrade
	@cd $(BACKEND_DIR) && $(COMPOSER) update
	@echo -e "${GREEN}${CHECK} Dependencies updated${NC}\n"
	
	@echo -e "${PURPLE}${INFO} Building applications...${NC}"
	@cd $(FRONTEND_DIR) && $(YARN) build
	@cd $(BACKEND_DIR) && $(COMPOSER) dump-autoload
	@cd $(BACKEND_DIR) && $(COMPOSER) install --optimize-autoloader
	@echo -e "${GREEN}${CHECK} Build complete${NC}\n"
	
	@echo -e "${GREEN}${ROCKET} Release build successful!${NC}\n"
lint: 
	@cd $(FRONTEND_DIR) && $(YARN) lint
	@cd $(BACKEND_DIR) && $(COMPOSER) run lint
	@cd $(FRONTEND_DIR) && $(YARN) format
# Install dependencies
install:
	@echo -e "\n${BOLD}${BLUE}Installing Dependencies${NC} ${PACKAGE}"
	@echo -e "${CYAN}=======================${NC}"
	@echo -e "${GREEN}${INFO} Installing frontend packages...${NC}"
	@cd $(FRONTEND_DIR) && $(YARN) install
	@echo -e "${GREEN}${CHECK} Frontend packages installed${NC}\n"
	@echo -e "${GREEN}${INFO} Installing backend packages...${NC}"
	@cd $(BACKEND_DIR) && $(COMPOSER) install
	@echo -e "${GREEN}${CHECK} Backend packages installed${NC}\n"

# Clean build artifacts
clean:
	@echo -e "\n${BOLD}${BLUE}Cleaning Artifacts${NC} ${CLEAN}"
	@echo -e "${CYAN}=======================${NC}"
	@echo -e "${YELLOW}${WARN} Removing artifacts and caches...${NC}"
	@cd $(FRONTEND_DIR) && rm -rf dist node_modules/
	@cd $(BACKEND_DIR) && rm -rf storage/caches/* storage/logs/* storage/packages/ public/attachments/
	@echo -e "${GREEN}${CHECK} Clean complete!${NC}\n"

# Run tests
test:
	@echo -e "\n${BOLD}${BLUE}Running Tests${NC} ${CHECK}"
	@echo -e "${CYAN}=============${NC}"
	@echo -e "${GREEN}${INFO} Running backend tests...${NC}"
	@cd $(BACKEND_DIR) && $(COMPOSER) test
	@echo -e "${GREEN}${CHECK} All tests complete!${NC}\n"