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
    private $security;

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

    /**
     * @param Environment $environment - Instances of this class are used to load templates.
     * 
     * @return string
     */
    public function renderMenu(Environment $environment): string
    {
        $items = [];

        $courses = [
            'name' => 'courses',
            'label' => 'Courses',
            'path' => 'course_index',
        ];

        $users = [
            'name' => 'users',
            'label' => 'Users',
            'path' => 'user_index',
        ];

        $teachers = [
            'name' => 'teachers',
            'label' => 'Teachers',
            'path' => 'teacher_index',
        ];

        $students = [
            'name' => 'students',
            'label' => 'Students',
            'path' => 'student_index',
        ];

        $classes = [
            'name' => 'classes',
            'label' => 'Classes',
            'path' => 'classes_index',
        ];

        $marks = [
            'name' => 'marks',
            'label' => 'Marks',
            'path' => 'mark_index',
        ];
        
        $user = $this->security->getUser();
        $currentUserRoles = $user->getRoles();

        if (in_array(User::ROLE_ADMIN, $currentUserRoles, true)) {

            //add arrays to $items[]
            array_push($items, $courses, $users, $teachers, $students, $classes, $marks);

            return $this->returnRender($environment, $items);
        }

        if (in_array(User::ROLE_TEACHER, $currentUserRoles, true)) {

            //add arrays to $items[]
            array_push($items, $courses, $teachers, $students, $classes, $marks);

            return $this->returnRender($environment, $items);
        }

        if (in_array(User::ROLE_STUDENT, $currentUserRoles, true)) {
            
            //add arrays to $items[]
            array_push($items, $courses, $teachers, $students, $classes, $marks);

            return $this->returnRender($environment, $items);
        }

        //add arrays to $items[]
        array_push($items, $courses, $teachers, $students, $classes, $marks);

        return $this->returnRender($environment, $items);
    }

    /**
     * @param string $name - name of page
     * 
     * @return string
     */
    public function getCurrentStyle($name): string
    {
        $request = $this->requestStack->getMasterRequest();
        $currentUrl = $request->getUri();

        if(strstr($currentUrl, $name) !== false) {
            return 'current-menu-page';
        }
        return 'menu-page';
    }
   
    /**
     * @param Environment $environment - Instances of this class are used to load templates.
     * @param array $items - array container.
     * 
     * @return string
     */
    private function returnRender(Environment $environment, array $items): string
    {
        return $environment->render(
            'partial/_menu.html.twig',
            [
               'items' => $items,
            ]
        );
    }
}
