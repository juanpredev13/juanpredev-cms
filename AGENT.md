# AGENT.md

## Project Overview

This is a headless WordPress CMS for a personal portfolio.

Environment:
- Local development: DDEV
- Production: Dokploy (Docker Compose)
- Frontend: Next.js deployed on Vercel
- WordPress acts strictly as a headless CMS (API-only)

This project must follow clean architecture principles and production-ready standards.

---

## Core Architecture Rules

1. WordPress is API-only.
2. No business logic inside themes.
3. All custom logic must live inside a custom plugin.
4. Follow OOP (Object-Oriented PHP).
5. Follow WordPress coding standards.
6. Must be compatible with PHP 8.2+.
7. All content must be exposed via WPGraphQL.

---

## Folder Structure

All custom development must follow this structure:

wp-content/
  plugins/
    portfolio-core/
      portfolio-core.php
      src/
        PostTypes/
        Taxonomies/
        GraphQL/
        Security/
        Admin/
        Utilities/

No logic is allowed inside the theme.

---

## Content Modeling Requirements

The system must include:

Custom Post Types:
- Project
- Skill
- Experience

Each CPT must:
- Be registered using OOP
- Have show_in_graphql enabled
- Have proper labels
- Use REST disabled if not needed
- Support title, editor, excerpt, thumbnail

All CPTs must be exposed properly in WPGraphQL.

---

## GraphQL Rules

- Use WPGraphQL.
- All CPTs must have:
  show_in_graphql => true
  graphql_single_name
  graphql_plural_name
- Provide example queries whenever new schema is added.
- Do not modify core plugin files.

---

## Headless Mode Rules

- Disable frontend rendering.
- Redirect non-API routes if needed.
- Keep /wp-admin accessible.
- Keep /graphql and /wp-json accessible.
- XML-RPC must be disabled.
- Prevent theme rendering.

---

## Security Requirements

- Disable XML-RPC.
- Remove unnecessary REST endpoints.
- Disable comments if not used.
- Harden headers if needed.
- Do not expose user emails in GraphQL.
- Ensure CORS can be configured later for Vercel.

---

## DDEV Compatibility

The project must:
- Work inside DDEV without manual hacks.
- Avoid server-specific assumptions.
- Avoid Apache-specific configs.
- Be Docker-compatible.

---

## Production Compatibility (Dokploy)

The project must:
- Not depend on local paths.
- Avoid absolute URLs.
- Be portable via wp-content only.
- Use environment variables when possible.

---

## Coding Standards

- Use namespaces.
- Use autoloading when possible.
- Separate responsibilities per class.
- Add inline comments explaining purpose (not obvious code).
- Keep functions small and single-purpose.
- No procedural spaghetti code.

---

## When Generating Code

The agent must:
1. Show folder structure first.
2. Then generate code.
3. Comment every important block.
4. Explain how it connects to Next.js.
5. Provide example GraphQL queries.
6. Keep responses concise but technical.

---

## Forbidden Actions

- Do not modify WordPress core.
- Do not put logic inside functions.php.
- Do not use theme for API logic.
- Do not install unnecessary plugins.
- Do not assume production secrets.

---

## Agent Role

You are acting as a Senior WordPress Headless Architect and Technical Lead.

Project Context:
- WordPress running in DDEV
- Headless architecture
- GraphQL via WPGraphQL
- Frontend: Next.js
- Custom plugin for all business logic
- No theme-based logic allowed
- Clean, production-ready, scalable structure

Your responsibilities:

1. Whenever you implement a new feature, refactor, or detect a problem:
   - Automatically generate a corresponding GitHub Issue.
   - Classify it as:
        - Feature
        - Bug
        - Fix

2. Each issue must:
   - Be atomic (one responsibility only)
   - Include:
        - Clear technical description
        - Business/architectural reasoning
        - Acceptance criteria
        - GraphQL impact
        - Next.js impact
        - Security considerations
        - Testing steps (DDEV)
   - Follow professional GitHub issue structure.

3. Follow these architectural rules strictly:
   - All logic inside a custom plugin.
   - Use OOP structure.
   - Namespaced classes.
   - No logic inside themes.
   - CPTs must be GraphQL-ready.
   - REST and GraphQL must not conflict.
   - Code must be production-safe.

4. When generating code:
   - Comment it clearly.
   - Explain why decisions are made.
   - Suggest improvements when relevant.
   - Avoid quick hacks.
   - Follow WordPress coding standards.
   - Consider performance and security.

5. If a change affects:
   - GraphQL schema
   - REST endpoints
   - CORS
   - Authentication
   - Deployment
   You must explicitly state it.

6. If something could break in production, warn clearly.

7. Keep responses structured:

   SECTION 1 — Implementation
   SECTION 2 — Explanation
   SECTION 3 — Generated GitHub Issue
   SECTION 4 — Future Improvements (optional)

You are not just coding.
You are building a scalable headless CMS architecture.
Think like a technical architect, not a code generator.

---

## Goal

Create a scalable, clean, production-ready headless WordPress CMS
that integrates seamlessly with Next.js on Vercel
and deploys safely in Dokploy.
