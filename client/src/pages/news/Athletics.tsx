import CategoryStoriesPage from "@/components/CategoryStoriesPage";

const Athletics = () => {
  return (
    <CategoryStoriesPage
      defaultCategory="Athletics"
      title="Athletics News"
      description="Latest news, updates, and stories from the world of athletics and running."
      showAllCategories={false}
    />
  );
};

export default Athletics;