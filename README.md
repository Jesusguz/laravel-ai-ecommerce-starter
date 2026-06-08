# VendIQ 🧠🛒 - Enterprise RAG AI E-Commerce Starter Kit

[![Laravel 13](https://img.shields.io/badge/Laravel-13-FF2D20.svg?style=flat&logo=laravel)](https://laravel.com)
[![React](https://img.shields.io/badge/React-19-61DAFB.svg?style=flat&logo=react)](https://reactjs.org/)
[![Pinecone](https://img.shields.io/badge/Vector_DB-Pinecone-000000.svg?style=flat)](#)
[![Prism](https://img.shields.io/badge/AI_Engine-Prism-5C6BC0.svg?style=flat)](#)

VendIQ is a high-performance, Multi-AI semantic search and sales assistant engine designed for modern e-commerce platforms. 

Built with a strict **Hexagonal Architecture**, it acts as a plug-and-play RAG (Retrieval-Augmented Generation) system that prevents AI hallucinations by grounding responses strictly in your vector-indexed product catalog.

## 🚀 Architectural Highlights

*   **Multi-AI Adapter Pattern:** Agnostic integration with Gemini, OpenAI, Claude, and Ollama. Switch providers seamlessly via `.env` without altering application logic.
*   **Matryoshka Representation Learning (MRL) Slicing:** Automatically normalizes high-dimensional embeddings (e.g., truncating 3072 dimensions to 768) to ensure mathematically safe upserts into Pinecone without index corruption.
*   **Resilient Context Retrieval:** Dynamic fallback mechanisms and exponential backoff handling for API rate limits and provider overloads.
*   **Server-Sent Events (SSE):** Real-time, token-by-token text streaming via Generator endpoints for a zero-latency UX.
*   **Premium Multi-Tenant Dashboard:** Built with Laravel Breeze (Inertia.js + React), providing an elegant, internationalized (EN/ES) interface for merchants to sync their Shopify catalogs and manage their configuration.

## ⚙️ Tech Stack

*   **Core:** PHP 8.3+, Laravel 13
*   **Frontend (Dashboard):** React 19, Inertia.js, Tailwind CSS v4
*   **Vector Database:** Pinecone (Cosine metric, 768 dimensions)
*   **LLM Orchestration:** Prism-PHP

## 📦 Core Workflows

1.  **Catalog Ingestion:** `php artisan rag:shopify-sync` pulls live active products from the Shopify Admin API and normalizes them into the local database.
2.  **Vectorization:** `php artisan rag:vectorize` converts product metadata into 768-dimension embeddings and persists them in Pinecone.
3.  **Inference (Streaming):** `POST /api/v1/chat/stream` processes user queries, retrieves the top semantic matches from Pinecone, and streams the AI-generated sales response.

## 🛡️ The Hexagonal Approach

VendIQ isolates the Domain logic from external dependencies. The `CommerceAIEngineInterface` ensures that whether you use Gemini 1.5 Flash or GPT-4o-mini, the application's action layer (`ProcessRagChat`) remains untouched.

```php
// Decoupled AI streaming execution
public function handleStream(string $userMessage): Generator
{
    return $this->aiEngine->streamChat(
        $this->formatMessages($userMessage),
        $this->buildContext($userMessage)
    );
}