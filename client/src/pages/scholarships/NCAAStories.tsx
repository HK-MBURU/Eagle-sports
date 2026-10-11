import CategoryStoriesPage from "@/components/CategoryStoriesPage";

const NCAAStories = () => {
  return (
    <CategoryStoriesPage
      defaultCategory="NCAA Stories"
      title="NCAA Stories"
      description="Discover inspiring stories of Kenyan athletes pursuing NCAA scholarships and excelling in American collegiate athletics."
      showAllCategories={false}
    />
  );
};

export default NCAAStories;