
import { onMounted } from 'vue';

interface PageOptions {
    layout?: {
        breadcrumbs?: Array<{
            title: string;
            href: string
        }>;
    };
}

export function useDefinePage(options: PageOptions) {
    return options;
}
