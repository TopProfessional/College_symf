<?php

namespace App\Twig;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;

class MenuExtension extends AbstractExtension
{
    private RequestStack $requestStack;
    private Security $security;

    public function __construct(Security $security, RequestStack $requestStack)
    {
       $this->security = $security;
       $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('renderMenu', [$this, 'renderMenu'], [
                'needs_environment' => true,
                'is_safe' => ['html']
            ]),
            new TwigFunction('getCurrentStyle', [$this, 'getCurrentStyle']),
        ];
    }

    public function renderMenu(Environment $environment): string
    {
        $items = $this->getItems();
        $sortedItems = $this->sortItems($items);

        return $environment->render(
            'partial/_menu.html.twig',
            [
               'items' => $sortedItems,
            ]
        );
    }

    /**
     * @param array<array<string,string|bool>> $items
     */
    private function sortItems(array $items): array
    {
        $request = $this->requestStack->getMasterRequest();
        $requestedRoute = $request->attributes->get('_route');

        foreach($items as $item) {
            if($item['route_name'] === $requestedRoute || strstr($requestedRoute, $item['children_routes_prefix']) !== false) {
                $item['style_class_name'] = 'active-link';
            }
            $sortedItems[] = $item;
        }
        return $sortedItems;
    }

    /**
     * @return array<array<string,string|bool>>
     */
    private function getItems(): array
    {
        $items = [];

        $courses = [
            'label' => 'Courses',
            'route_name' => 'course_index',
            'children_routes_prefix' => 'course_',
            'style_class_name' => '',
        ];

        $users = [
            'label' => 'Users',
            'route_name' => 'user_index',
            'children_routes_prefix' => 'user_',
            'style_class_name' => '',
        ];

        $teachers = [
            'label' => 'Teachers',
            'route_name' => 'teacher_index',
            'children_routes_prefix' => 'teacher_',
            'style_class_name' => '',
        ];

        $students = [
            'label' => 'Students',
            'route_name' => 'student_index',
            'children_routes_prefix' => 'student_',
            'style_class_name' => '',
        ];

        $classes = [
            'label' => 'Classes',
            'route_name' => 'classes_index',
            'children_routes_prefix' => 'classes_',
            'style_class_name' => '',
        ];

        $marks = [
            'label' => 'Marks',
            'route_name' => 'mark_index',
            'children_routes_prefix' => 'mark_',
            'style_class_name' => '',
        ];
        
        $user = $this->security->getUser();

        if ($user->hasRole(User::ROLE_ADMIN)) {
            $items = [$courses, $users, $teachers, $students, $classes, $marks];
            return $items;
        }

        if ($user->hasRole(User::ROLE_TEACHER)) {
            $items = [$courses, $teachers, $students, $classes, $marks];
            return $items;
        }

        if ($user->hasRole(User::ROLE_STUDENT)) {
            $items = [$courses, $teachers, $students, $classes, $marks];
            return $items;
        }

        $items = [$courses, $teachers, $students, $classes, $marks];
        return $items;
    }
}
