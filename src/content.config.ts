import { defineCollection } from 'astro:content';
import { z } from 'astro/zod';
import { glob } from 'astro/loaders';

const seo = z.object({
    title: z.string().optional(),
    description: z.string().optional(),
    noindex: z.boolean().optional()
}).optional();

const event = z.object({
    start: z.coerce.date(),
    end: z.coerce.date().optional(),
    location: z.string().optional(),
    description: z.string().optional(),
}).optional();

const nieuws = defineCollection({
    loader: glob({
        base: './src/content/nieuws',
        pattern: '**/*.{md,mdx}'
    }),
    schema: z.object({
        title: z.string(),
        description: z.string(),
        date: z.coerce.date(),
        image: z.string().optional(),
        imageAlt: z.string().default(''),
        tags: z.array(z.string()).default([]),
        event,
        seo,
    }),
});

const media = defineCollection({
    loader: glob({
        base: './src/content/media',
        pattern: '**/*.{md,mdx}'
    }),
    schema: z.object({
        title: z.string(),
        description: z.string(),
        images: z.array(z.object({
            src: z.string(),
            alt: z.string().optional()
        }))
    })
});

export const collections = {
    nieuws,
    media
};
